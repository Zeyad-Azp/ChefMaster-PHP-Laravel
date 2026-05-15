<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpoonacularController extends Controller
{
    protected string $baseUrl;

    /** @var string[] All available API keys */
    protected array $apiKeys;

    public function __construct()
    {
        $this->baseUrl = config('services.spoonacular.base_url');

        // Load multiple keys from comma-separated env, fallback to single key
        $multiKeys = config('services.spoonacular.keys');
        if ($multiKeys) {
            $this->apiKeys = array_filter(array_map('trim', explode(',', $multiKeys)));
        }

        // Fallback: if no multi-key list, use the single key
        if (empty($this->apiKeys)) {
            $singleKey = config('services.spoonacular.key');
            $this->apiKeys = $singleKey ? [$singleKey] : [];
        }
    }

    /**
     * Pick a random API key from the pool.
     */
    protected function randomKey(): string
    {
        return $this->apiKeys[array_rand($this->apiKeys)];
    }

    /**
     * Execute an HTTP GET with random key selection and automatic retry on 402 (quota exceeded).
     *
     * @param string $url      Full endpoint URL
     * @param array  $params   Query parameters (apiKey will be injected)
     * @param int    $maxRetries
     * @return \Illuminate\Http\Client\Response
     */
    protected function apiGet(string $url, array $params = [], int $maxRetries = 3)
    {
        $triedKeys = [];

        for ($attempt = 0; $attempt < min($maxRetries, count($this->apiKeys)); $attempt++) {
            // Pick a random key that hasn't been tried yet
            $availableKeys = array_diff($this->apiKeys, $triedKeys);
            if (empty($availableKeys)) break;

            $key = $availableKeys[array_rand($availableKeys)];
            $triedKeys[] = $key;

            $params['apiKey'] = $key;

            $response = Http::get($url, $params);

            // 402 = Payment Required (quota exceeded) — try another key
            if ($response->status() === 402) {
                Log::warning('SpoonacularController: API key quota exceeded, trying next key.', [
                    'key_suffix' => '...' . substr($key, -6),
                    'attempt'    => $attempt + 1,
                ]);
                continue;
            }

            return $response;
        }

        // All keys exhausted — return last response (402)
        Log::error('SpoonacularController: All API keys exhausted (402 on all).');
        return $response ?? Http::get($url, $params);
    }

    public function search(Request $request)
    {
        try {
            $query  = $request->query('query');
            $offset = (int) $request->query('offset', 0);
            $mode   = $request->query('mode', 'name') === 'ingredients' ? 'ingredients' : 'name';

            if (!$query) {
                return response()->json([
                    'success' => false,
                    'message' => 'Query parameter is required'
                ], 400);
            }

            $params = [
                'offset'               => $offset,
                'number'               => 12,
                'addRecipeNutrition'    => 'true',
                'addRecipeInformation'  => 'true',
            ];

            if ($mode === 'ingredients') {
                // Comma-separated ingredient list — Spoonacular's complexSearch
                // accepts includeIngredients to filter recipes by what's on hand.
                $params['includeIngredients'] = $query;
                $params['fillIngredients']    = 'true';
                $params['ranking']            = 1;
            } else {
                $params['query'] = $query;
            }

            $response = $this->apiGet($this->baseUrl . '/recipes/complexSearch', $params);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch recipes'
                ], $response->status());
            }

            $raw = $response->json();

            // Flatten nutrition data for each result so the frontend gets simple fields
            $results = collect($raw['results'] ?? [])->map(function ($item) {
                $nutrients = collect($item['nutrition']['nutrients'] ?? []);
                $get = fn($name) => round($nutrients->firstWhere('name', $name)['amount'] ?? 0, 1);

                $item['calories']       = $get('Calories');
                $item['protein']        = $get('Protein');
                $item['carbs']          = $get('Carbohydrates');
                $item['fats']           = $get('Fat');
                $item['readyInMinutes'] = $item['readyInMinutes'] ?? null;

                return $item;
            })->toArray();

            $raw['results'] = $results;

            return response()->json([
                'success' => true,
                'data' => $raw
            ]);
        } catch (\Throwable $e) {
            Log::error('SpoonacularController@search error', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        try {
            $recipeId = $request->query('recipe_id');

            // Bug fix: validate that recipe_id is a positive integer to prevent SSRF / path injection
            if (!$recipeId || !ctype_digit((string) $recipeId) || (int) $recipeId < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'recipe_id must be a positive integer.',
                ], 400);
            }

            $response = $this->apiGet(
                $this->baseUrl . '/recipes/' . (int) $recipeId . '/information',
                ['includeNutrition' => true]
            );

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch recipe detail'
                ], $response->status());
            }

            $raw = $response->json();

            // Spoonacular should always return at least id+title for a valid lookup;
            // bail out with 502 (bad upstream response) if either is missing.
            if (!is_array($raw) || empty($raw['id']) || empty($raw['title'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upstream returned an unexpected response.',
                ], 502);
            }

            // Flatten nutrition so the frontend gets simple fields
            $nutrients = collect($raw['nutrition']['nutrients'] ?? []);
            $get = fn($name) => $nutrients->firstWhere('name', $name)['amount'] ?? 0;

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'             => $raw['id'],
                    'title'          => $raw['title'],
                    'image'          => $raw['image'] ?? null,
                    'description'    => strip_tags($raw['summary'] ?? ''),
                    'readyInMinutes' => $raw['readyInMinutes'] ?? null,
                    'servings'       => $raw['servings'] ?? null,
                    'ingredients'    => collect($raw['extendedIngredients'] ?? [])
                                            ->pluck('original')
                                            ->implode("\n"),
                    'instructions'   => collect($raw['analyzedInstructions'][0]['steps'] ?? [])
                                            ->pluck('step')
                                            ->implode("\n"),
                    'calories'       => $get('Calories'),
                    'protein'        => $get('Protein'),
                    'carbs'          => $get('Carbohydrates'),
                    'fats'           => $get('Fat'),
                ]
            ]);

        } catch (\Throwable $e) {
            Log::error('SpoonacularController@detail error', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            // Bug fix: api_recipe_id must be a positive integer (Spoonacular IDs are always numeric)
            $request->validate([
                'api_recipe_id' => 'required|integer|min:1',
                'title'         => 'required|string|max:255',
                'description'   => 'nullable|string|max:5000',
                'ingredients'   => 'nullable|string|max:5000',
                'instructions'  => 'nullable|string|max:10000',
                'image_path'    => 'nullable|string|max:500',
                'calories'      => 'nullable|numeric|min:0|max:99999',
                'protein'       => 'nullable|numeric|min:0|max:9999',
                'carbs'         => 'nullable|numeric|min:0|max:9999',
                'fats'          => 'nullable|numeric|min:0|max:9999',
                'is_favorite'   => 'sometimes|boolean',
            ]);

            // Prevent duplicates — but allow upgrading to favourite
            $exists = \App\Models\Recipe::where('api_recipe_id', $request->api_recipe_id)
                ->where('user_id', auth()->id())
                ->first();

            if ($exists) {
                // If the caller wants to favourite and it isn't yet, update it
                if ($request->is_favorite && !$exists->is_favorite) {
                    $exists->update(['is_favorite' => 1]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Added to your Favourites!',
                        'data'    => $exists->fresh(),
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Recipe already saved to your collection.'
                ], 409);
            }

            $recipe = \App\Models\Recipe::create([
                'api_recipe_id' => $request->api_recipe_id,
                'title'         => $request->title,
                'description'   => $request->description   ?? '',
                'ingredients'   => $request->ingredients   ?? '',
                'instructions'  => $request->instructions  ?? '',
                'calories'      => $request->calories      ?? 0,
                'protein'       => $request->protein       ?? 0,
                'carbs'         => $request->carbs         ?? 0,
                'fats'          => $request->fats          ?? 0,
                'image_path'    => $request->image_path    ?? null,
                'is_favorite'   => $request->is_favorite   ?? 0,
                'source_type'   => 'api',
                'user_id'       => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recipe saved to your collection!',
                'data'    => $recipe,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('SpoonacularController@save error', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }
}
