/**
 * API_Ops.js — ChefMaster Frontend API Module (v2 — refined)
 * Routes all Spoonacular calls through API_Ops.php.
 * Keys never touch the browser.
 * Now supports page-based pagination.
 */
    const API_Ops = (() => {
    const DB_ENDPOINT = 'DB_Ops.php';

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

    const url = `/api/spoonacular/search?query=${encodeURIComponent(query)}&offset=${offset}`;

    const res = await fetch(url);

    const json = await res.json();

    return json; // IMPORTANT
}

    function renderApiRecipes(recipes) {

    const apiResults = document.getElementById('apiResults');

    if (!recipes.length) {
        apiResults.innerHTML = `
            <div class="empty-state">
                No recipes found.
            </div>
        `;
        return;
    }

    apiResults.innerHTML = `
        <div class="recipes-grid">
            ${recipes.map(recipe => `
                <div class="recipe-card">
                    <img
                        src="${recipe.image}"
                        alt="${recipe.title}"
                        class="recipe-image"
                    >

                    <div class="recipe-card-body">
                        <h3 class="recipe-title">
                            ${recipe.title}
                        </h3>

                        <button
                            class="btn btn-primary save-api-btn"
                            data-id="${recipe.id}"
                        >
                            Save
                        </button>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

    // ── Get full recipe detail from Spoonacular ──────────────
    async function getRecipeDetail(recipeId) {
        const fd = new FormData();
        fd.append('action', 'detail');
        fd.append('recipe_id', recipeId);
        return post('/api/spoonacular/detail', fd);
    }

    // ── Save an API recipe to the local DB ───────────────────
    async function saveApiRecipe(recipeData, isFavorite = 0) {
        const fd = new FormData();
        fd.append('action', 'save_api_recipe');
        fd.append('api_recipe_id', recipeData.id);
        fd.append('title', recipeData.title);
        fd.append('description', recipeData.description || '');
        fd.append('ingredients', recipeData.ingredients || '');
        fd.append('instructions', recipeData.instructions || '');
        fd.append('calories', recipeData.calories || 0);
        fd.append('protein', recipeData.protein || 0);
        fd.append('carbs', recipeData.carbs || 0);
        fd.append('fats', recipeData.fats || 0);
        fd.append('image_path', recipeData.image || '');
        fd.append('is_favorite', isFavorite ? 1 : 0);
        const res = await fetch('DB_Ops.php', { method: 'POST', body: fd });
        // allow 409 through instead of throwing
        if (!res.ok && res.status !== 409) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    async function isFavorite(recipeId) {
        const fd = new FormData();
        fd.append('action', 'is_favorite');
        fd.append('id', recipeId);

        const res = await fetch('API_Ops.php', {
            method: 'POST',
            body: fd
        });

        return await res.json();
    }

    // Public API
    return { searchRecipes, getRecipeDetail, saveApiRecipe };

})();
