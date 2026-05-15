<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

class RecipeController extends Controller
{
    /**
     * Display a listing of all recipes.
     * Route: GET /recipes
     */
    public function index()
    {
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();
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
    public function store(StoreRecipeRequest $request)
    {
        $validated = $request->validated();

        $validated['calories']    = $validated['calories']    ?? 0;
        $validated['protein']     = $validated['protein']     ?? 0;
        $validated['carbs']       = $validated['carbs']       ?? 0;
        $validated['fats']        = $validated['fats']        ?? 0;
        $validated['source_type'] = 'manual';
        $validated['user_id']     = auth()->id();

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
        // Enforce ownership for ALL request types (web and AJAX)
        if ($recipe->user_id !== auth()->id()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

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
        if ($recipe->user_id !== auth()->id()) {
            abort(403);
        }
        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified recipe in the database.
     * Route: PUT /recipes/{recipe}
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

        $validated = $request->validated();
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
        if ($recipe->user_id !== auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

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
     */
    public function toggleFavorite(Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $recipe->update(['is_favorite' => !$recipe->is_favorite]);

        return response()->json([
            'success'     => true,
            'is_favorite' => $recipe->is_favorite,
            'message'     => $recipe->is_favorite
                ? 'Added to favorites!'
                : 'Removed from favorites.',
        ]);
    }

    /**
     * Return current user's recipes as JSON.
     * Route: GET /recipes/api/list
     */
    public function apiList()
    {
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $recipes,
        ]);
    }

    /**
     * Return current user's favorites as JSON.
     * Route: GET /recipes/api/favorites
     */
    public function apiFavorites()
    {
        $favorites = Recipe::where('user_id', auth()->id())
                           ->where('is_favorite', true)
                           ->latest()
                           ->get();

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
