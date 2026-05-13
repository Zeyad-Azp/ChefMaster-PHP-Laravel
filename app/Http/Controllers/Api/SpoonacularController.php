<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpoonacularController extends Controller
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.spoonacular.base_url');
        $this->apiKey = config('services.spoonacular.key');
    }

    public function search(Request $request)
    {
        try {
            $query = $request->query('query');
            $offset = (int) $request->query('offset', 0);

            if (!$query) {
                return response()->json([
                    'success' => false,
                    'message' => 'Query parameter is required'
                ], 400);
            }

            $response = Http::get(
                $this->baseUrl . '/recipes/complexSearch',
                [
                    'apiKey' => $this->apiKey,
                    'query' => $query,
                    'offset' => $offset,
                    'number' => 12,
                ]
            );

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch recipes'
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        try {
            $recipeId = $request->query('recipe_id');

            if (!$recipeId) {
                return response()->json([
                    'success' => false,
                    'message' => 'recipe_id is required'
                ], 400);
            }

            $response = Http::get(
                $this->baseUrl . '/recipes/' . $recipeId . '/information',
                [
                    'apiKey'            => $this->apiKey,
                    'includeNutrition'  => true,
                ]
            );

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch recipe detail'
                ], $response->status());
            }

            $raw = $response->json();

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
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        //return response()->json(['debug' => 'hit', 'data' => $request->all()]);
        try {
            $request->validate([
                'api_recipe_id' => 'required',
                'title'         => 'required|string|max:255',
            ]);

            // Prevent duplicates
            $exists = \App\Models\Recipe::where('api_recipe_id', $request->api_recipe_id)->first();
            if ($exists) {
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
                'user_id'       => null,
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
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
