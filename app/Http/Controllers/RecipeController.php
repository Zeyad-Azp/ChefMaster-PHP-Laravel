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
<<<<<<< HEAD
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();
=======
        $recipes = Recipe::latest()->get();
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
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
<<<<<<< HEAD
=======
     * T5 (M5): Validation handled by StoreRecipeRequest Form Request.
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
     */
    public function store(StoreRecipeRequest $request)
    {
        $validated = $request->validated();

<<<<<<< HEAD
=======
        // Set defaults for nullable numeric fields
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        $validated['calories']    = $validated['calories']    ?? 0;
        $validated['protein']     = $validated['protein']     ?? 0;
        $validated['carbs']       = $validated['carbs']       ?? 0;
        $validated['fats']        = $validated['fats']        ?? 0;
<<<<<<< HEAD
        $validated['source_type'] = 'manual';
        $validated['user_id']     = auth()->id();
=======

        // source_type defaults to 'manual' for user-created recipes
        $validated['source_type'] = 'manual';

        // TODO: Replace with auth()->id() when authentication is added
        $validated['user_id'] = null;
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a

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
<<<<<<< HEAD
        // Enforce ownership for ALL request types (web and AJAX)
        if ($recipe->user_id !== auth()->id()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

=======
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $recipe,
            ]);
        }
<<<<<<< HEAD

=======
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified recipe.
     * Route: GET /recipes/{recipe}/edit
     */
    public function edit(Recipe $recipe)
    {
<<<<<<< HEAD
        if ($recipe->user_id !== auth()->id()) {
            abort(403);
        }
=======
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified recipe in the database.
     * Route: PUT /recipes/{recipe}
<<<<<<< HEAD
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

=======
     * T5 (M5): Validation handled by UpdateRecipeRequest Form Request.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
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
<<<<<<< HEAD
        if ($recipe->user_id !== auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

=======
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
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
<<<<<<< HEAD
     */
    public function toggleFavorite(Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

=======
     * Used by AJAX calls from the frontend JS (M7)
     */
    public function toggleFavorite(Recipe $recipe)
    {
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
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
<<<<<<< HEAD
     * Return current user's recipes as JSON.
=======
     * Return all recipes as JSON — used by frontend AJAX (M7).
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
     * Route: GET /recipes/api/list
     */
    public function apiList()
    {
<<<<<<< HEAD
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();
=======
        $recipes = Recipe::latest()->get();
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a

        return response()->json([
            'success' => true,
            'data'    => $recipes,
        ]);
    }

    /**
<<<<<<< HEAD
     * Return current user's favorites as JSON.
=======
     * Return favorites as JSON — used by frontend AJAX (M7).
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
     * Route: GET /recipes/api/favorites
     */
    public function apiFavorites()
    {
<<<<<<< HEAD
        $favorites = Recipe::where('user_id', auth()->id())
                           ->where('is_favorite', true)
                           ->latest()
                           ->get();
=======
        $favorites = Recipe::where('is_favorite', true)->latest()->get();
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a

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
