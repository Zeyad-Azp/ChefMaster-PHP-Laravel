/**
 * API_Ops.js — ChefMaster Frontend API Module (v2 — refined)
 * Routes all Spoonacular calls through API_Ops.php.
 * Keys never touch the browser.
 * Now supports page-based pagination.
 */
    const API_Ops = (() => {

    // ── Generic POST helper ──────────────────────────────────
    async function post(endpoint, formData) {
        const res = await fetch(endpoint, { method: 'POST', body: formData });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    // ── Search recipes via Spoonacular ───────────────────────
    // @param query     string  — search keywords
    // @param searchType string — 'name' | 'ingredients'
    // @param page      number  — 1-based page number (default 1)
   async function searchRecipes(query, searchType = 'name', page = 1) {

    const offset = (page - 1) * 12;
    const mode = searchType === 'ingredients' ? 'ingredients' : 'name';

    const url = `/api/spoonacular/search?query=${encodeURIComponent(query)}&offset=${offset}&mode=${mode}`;

    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    const json = await res.json();

    return json; // IMPORTANT
}

    // ── Get full recipe detail from Spoonacular ──────────────
    async function getRecipeDetail(recipeId) {
        const res = await fetch(`/api/spoonacular/detail?recipe_id=${recipeId}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    // ── Save an API recipe to the local DB ───────────────────
    async function saveApiRecipe(recipeData, isFavorite = 0) {
        const fd = new FormData();
        fd.append('api_recipe_id', recipeData.id);
        fd.append('title',         recipeData.title);
        fd.append('description',   recipeData.description  || '');
        fd.append('ingredients',   recipeData.ingredients  || '');
        fd.append('instructions',  recipeData.instructions || '');
        fd.append('calories',      recipeData.calories     || 0);
        fd.append('protein',       recipeData.protein      || 0);
        fd.append('carbs',         recipeData.carbs        || 0);
        fd.append('fats',          recipeData.fats         || 0);
        fd.append('image_path',    recipeData.image        || '');
        fd.append('is_favorite',   isFavorite ? 1 : 0);

        const res = await fetch('/api/spoonacular/save', {   // ← Laravel route
            method: 'POST',
            body: fd,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        });

        if (!res.ok && res.status !== 409) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }
    // Public API
    return { searchRecipes, getRecipeDetail, saveApiRecipe };

})();
