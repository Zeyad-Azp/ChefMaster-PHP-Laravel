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
}
