<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of all recipes.
     * Route: GET /recipes
     */
    public function index()
    {
        $recipes = Recipe::latest()->get();
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new recipe.
     * Route: GET /recipes/create
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store a newly created recipe in the database.
     * Route: POST /recipes
     */
    public function store(Request $request)
    {
        // Validation will be handled by M5 (T5) via Form Request
        // Basic inline validation as placeholder until T5 is merged
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'ingredients'  => 'required|string',
            'instructions' => 'required|string',
            'description'  => 'nullable|string',
            'calories'     => 'nullable|numeric|min:0',
            'protein'      => 'nullable|numeric|min:0',
            'carbs'        => 'nullable|numeric|min:0',
            'fats'         => 'nullable|numeric|min:0',
            'image_path'   => 'nullable|string|max:500',
        ]);

        // Set defaults for nullable numeric fields
        $validated['calories'] = $validated['calories'] ?? 0;
        $validated['protein']  = $validated['protein']  ?? 0;
        $validated['carbs']    = $validated['carbs']    ?? 0;
        $validated['fats']     = $validated['fats']     ?? 0;

        // source_type defaults to 'manual' for user-created recipes
        $validated['source_type'] = 'manual';

        // TODO: Replace with auth()->id() when authentication is added
        $validated['user_id'] = null;

        $recipe = Recipe::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Recipe created successfully!',
                'data'    => $recipe,
            ]);
        }
        return redirect()->route('recipes.show', $recipe)->with('success', 'Recipe created successfully!');
    }

    /**
     * Display the specified recipe.
     * Route: GET /recipes/{recipe}
     */
    public function show(Recipe $recipe)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $recipe,
            ]);
        }
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified recipe.
     * Route: GET /recipes/{recipe}/edit
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified recipe in the database.
     * Route: PUT /recipes/{recipe}
     */
    public function update(Request $request, Recipe $recipe)
    {
        // Validation will be enhanced by M5 (T5) — placeholder rules here
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'ingredients'  => 'required|string',
            'instructions' => 'required|string',
            'description'  => 'nullable|string',
            'calories'     => 'nullable|numeric|min:0',
            'protein'      => 'nullable|numeric|min:0',
            'carbs'        => 'nullable|numeric|min:0',
            'fats'         => 'nullable|numeric|min:0',
            'image_path'   => 'nullable|string|max:500',
        ]);

        $recipe->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Recipe updated successfully!',
                'data'    => $recipe->fresh(),
            ]);
        }
        return redirect()->route('recipes.show', $recipe)->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified recipe from the database.
     * Route: DELETE /recipes/{recipe}
     */
    public function destroy(Request $request, Recipe $recipe)
    {
        $recipe->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Recipe deleted successfully!',
            ]);
        }
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully!');
    }
    /**
     * Toggle the is_favorite status of a recipe.
     * Route: PATCH /recipes/{recipe}/favorite
     * Used by AJAX calls from the frontend JS (M7)
     */
    public function toggleFavorite(Recipe $recipe)
    {
        $recipe->update(['is_favorite' => !$recipe->is_favorite]);

        // Return JSON for AJAX calls
        return response()->json([
            'success'     => true,
            'is_favorite' => $recipe->is_favorite,
            'message'     => $recipe->is_favorite
                ? 'Added to favorites!'
                : 'Removed from favorites.',
        ]);
    }

    /**
     * Return all recipes as JSON — used by frontend AJAX (M7).
     * Route: GET /recipes/api/list
     */
    public function apiList()
    {
        $recipes = Recipe::latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $recipes,
        ]);
    }

    /**
     * Return favorites as JSON — used by frontend AJAX (M7).
     * Route: GET /recipes/api/favorites
     */
    public function apiFavorites()
    {
        $favorites = Recipe::where('is_favorite', true)->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $favorites,
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('uploads', 'public');

        return response()->json([
            'success'    => true,
            'image_path' => '/storage/' . $path,
        ]);
    }
}
