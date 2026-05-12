/**
 * script.js — ChefMaster SPA Core (v2 — refined)
 * Scroll-based single-page layout with improved pagination,
 * premium search hero, section separation, and better interactions.
 */

'use strict';

/* ── State ──────────────────────────────────────────────────── */
const State = {
    recipes: [],
    editingId: null,
    // Pagination
    currentPage: 1,
    totalPages: 1,
    totalResults: 0,
    lastQuery: '',
    lastMode: 'name',
    perPage: 12,
};

/* ── Icons (Lucide inline SVG) ───────────────────────────────── */
const Icon = {
    chef: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>`,
    plus: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>`,
    book: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>`,
    heart: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`,
    search: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>`,
    grid: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>`,
    edit: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`,
    trash: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>`,
    eye: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`,
    image: `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>`,
    upload: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>`,
    moon: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>`,
    sun: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`,
    close: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
    save: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>`,
    clock: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
    utensils: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>`,
    check: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
    alert: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
    info: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
    star: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
    empty: `<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>`,
    prev: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>`,
    next: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>`,
};

/* ── Theme ───────────────────────────────────────────────────── */
function initTheme() {
    const saved = localStorage.getItem('chefmaster_theme') || 'light';
    applyTheme(saved);
}
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('chefmaster_theme', theme);
    const btn = document.getElementById('themeToggle');
    if (btn) btn.innerHTML = theme === 'dark' ? Icon.sun : Icon.moon;
}
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'light';
    applyTheme(current === 'dark' ? 'light' : 'dark');
}

/* ── Toast ────────────────────────────────────────────────────── */
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const icons = {
        success: `<span class="toast-icon-success">${Icon.check}</span>`,
        error: `<span class="toast-icon-error">${Icon.alert}</span>`,
        info: `<span class="toast-icon-info">${Icon.info}</span>`,
    };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `${icons[type] || icons.info}<span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

/* ── DB fetch — updated for Laravel routes (M7) ─────────────── */
async function dbPost(formData, url = null) {
    const action = formData.get('action');
    const id = formData.get('id');

    // Map old DB_Ops.php actions to new Laravel routes
    let endpoint = '/recipes';
    let method = 'POST';

    if (action === 'get_recipes') {
        endpoint = '/recipes/api/list';
        method = 'GET';
    } else if (action === 'get_recipe') {
        endpoint = `/recipes/${id}`;
        method = 'GET';
    } else if (action === 'add_recipe') {
        endpoint = '/recipes';
        method = 'POST';
    } else if (action === 'update_recipe') {
        endpoint = `/recipes/${id}`;
        method = 'POST';
        formData.append('_method', 'PUT');
    } else if (action === 'delete_recipe') {
        endpoint = `/recipes/${id}`;
        method = 'POST';
        formData.append('_method', 'DELETE');
    } else if (action === 'toggle_favorite') {
        endpoint = `/recipes/${id}/favorite`;
        method = 'POST';
        formData.append('_method', 'PATCH');
    }

    const res = await fetch(endpoint, {
        method: method === 'GET' ? 'GET' : 'POST',
        body: method === 'GET' ? null : formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        }
    });

    if (!res.ok && res.status !== 409 && res.status !== 422) {
        throw new Error(`HTTP ${res.status}`);
    }

    return res.json();
}

/* ── Scroll helper ───────────────────────────────────────────── */
function scrollToSection(id) {
    const el = document.getElementById(id);
    if (el) {
        const offset = 80; // header height + breathing room
        const top = el.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
    }
}

/* ── Scroll observer — highlights active nav item ────────────── */
function initScrollObserver() {
    const sections = document.querySelectorAll('.page-section[id]');
    const navBtns = document.querySelectorAll('.nav-btn[data-nav], .mobile-nav-btn[data-nav]');

    const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                navBtns.forEach(btn => {
                    if (btn.dataset.nav !== 'addRecipe') {
                        btn.classList.toggle('active', btn.dataset.nav === entry.target.id);
                    }
                });
            }
        });
    }, { rootMargin: '-30% 0px -55% 0px', threshold: 0 });

    sections.forEach(s => io.observe(s));
}

/* ══════════════════════════════════════════════════════════════
   PAGE INIT
══════════════════════════════════════════════════════════════ */

async function initAllSections() {
   const recipesTitle = document.getElementById('recipesTitle');
    if (recipesTitle) {
        recipesTitle.innerHTML =
            `<span class="icon">${Icon.book}</span> My Recipes`;
    }

    const favTitle = document.getElementById('favTitle');
    if (favTitle) {
        favTitle.innerHTML =
            `<span class="icon">${Icon.heart}</span> Favourites`;
    }

    // ── Filter bar
    const fb = document.getElementById('filterBar');
    if (fb) {
        fb.innerHTML = `
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="manual">My Own</button>
            <button class="filter-btn" data-filter="api">From API</button>`;
        fb.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                fb.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updateRecipesView(this.dataset.filter);
            });
        });
    }

    // ── Premium search hero
    buildSearchHero();

    // ── Load initial data
    await loadRecipes();
}

/* ══════════════════════════════════════════════════════════════
   SEARCH HERO
══════════════════════════════════════════════════════════════ */

function buildSearchHero() {
    const wrap = document.getElementById('searchWrap');
    if (!wrap) return;

    // Replace #discoverTitle with heading inside hero
    const discoverTitle = document.getElementById('discoverTitle');
    if (discoverTitle) discoverTitle.innerHTML = '';

    wrap.innerHTML = `
        <div class="search-hero">
            <div class="search-hero-label">Discover Recipes</div>
            <div class="search-hero-sub">Search millions of dishes by name or ingredients</div>
            <div class="search-bar-outer">
                <select id="apiSearchMode" class="search-mode-select" aria-label="Search mode">
                    <option value="name">By Name</option>
                    <option value="ingredients">By Ingredients</option>
                </select>
                <div class="search-input-wrap">
                    <span class="search-icon" aria-hidden="true">${Icon.search}</span>
                    <input
                        type="text"
                        id="apiSearchInput"
                        class="search-input-field"
                        placeholder="e.g. chicken pasta, avocado toast…"
                        autocomplete="off"
                        aria-label="Recipe search">
                </div>
                <button class="btn btn-primary search-submit-btn" id="apiSearchBtn" onclick="doApiSearch()">
                    Search
                </button>
            </div>
            <div class="search-examples" id="searchExamples">
                Try:
                <span onclick="quickSearch('spaghetti carbonara')">spaghetti carbonara</span>
                <span onclick="quickSearch('chicken soup')">chicken soup</span>
                <span onclick="quickSearch('avocado toast')">avocado toast</span>
                <span onclick="quickSearch('chocolate cake')">chocolate cake</span>
            </div>
        </div>`;

    document.getElementById('apiSearchInput')
        .addEventListener('keydown', e => { if (e.key === 'Enter') doApiSearch(); });
}

function quickSearch(term) {
    const input = document.getElementById('apiSearchInput');
    if (input) {
        input.value = term;
        doApiSearch();
        scrollToSection('sectionDiscover');
    }
}

/* ══════════════════════════════════════════════════════════════
   DATA LOADING
══════════════════════════════════════════════════════════════ */

async function loadRecipes() {
    const grid = document.getElementById('recipesGrid');
    if (grid) grid.innerHTML = skeletonCards(6);

    try {
        const fd = new FormData();
        fd.append('action', 'get_recipes');
        const res = await dbPost(fd);
        if (res.success) {
            State.recipes = res.data || [];
            updateRecipesView('all');
            updateFavoritesView();
        } else {
            showToast('Failed to load recipes.', 'error');
        }
    } catch {
        showToast('Failed to load recipes.', 'error');
    }
}

function updateRecipesView(filter = 'all') {
    const container = document.getElementById('recipesGrid');
    if (!container) return;
    const filtered = filter === 'all'
        ? State.recipes
        : State.recipes.filter(r => r.source_type === filter);
    renderRecipeCards(container, filtered);
}

function updateFavoritesView() {
    const container = document.getElementById('favGrid');
    if (!container) return;
    const favs = State.recipes.filter(r => +r.is_favorite);
    if (!favs.length) {
        container.innerHTML = emptyState('No favourites yet', 'Heart a recipe to save it here.');
    } else {
        renderRecipeCards(container, favs);
    }
}

function renderRecipeCards(container, recipes) {
    if (!recipes.length) {
        container.innerHTML = emptyState('No recipes here', 'No recipes match this criteria.');
        return;
    }
    container.innerHTML = recipes.map(r => recipeCard(r)).join('');
}

/* ══════════════════════════════════════════════════════════════
   RECIPE CARD
══════════════════════════════════════════════════════════════ */

function recipeCard(r) {
    const isFav = +r.is_favorite;
    const badge = r.source_type === 'api'
        ? `<span class="recipe-badge api-badge">API</span>`
        : `<span class="recipe-badge">My Recipe</span>`;
    const imgHtml = r.image_path
        ? `<img src="${escHtml(r.image_path)}" alt="${escHtml(r.title)}" loading="lazy">`
        : `<div class="recipe-img-placeholder">${Icon.image}</div>`;

    return `<div class="recipe-card" data-id="${r.id}">
        <div class="recipe-img-wrap" onclick="openRecipeModal(${r.id})" role="button" tabindex="0" aria-label="View ${escHtml(r.title)}">
            ${imgHtml}${badge}
        </div>
        <div class="recipe-body">
            <div class="recipe-title" onclick="openRecipeModal(${r.id})" role="button" tabindex="0">${escHtml(r.title)}</div>
            <div class="recipe-desc">${escHtml(r.description || 'No description provided.')}</div>
            <div class="recipe-macros" aria-label="Nutrition info">
                <div class="macro-item"><div class="macro-val">${fmt(r.calories)}</div><div class="macro-key">kcal</div></div>
                <div class="macro-item"><div class="macro-val">${fmt(r.protein)}g</div><div class="macro-key">Protein</div></div>
                <div class="macro-item"><div class="macro-val">${fmt(r.carbs)}g</div><div class="macro-key">Carbs</div></div>
                <div class="macro-item"><div class="macro-val">${fmt(r.fats)}g</div><div class="macro-key">Fats</div></div>
            </div>
            <div class="recipe-actions">
                <button class="btn-action" onclick="openRecipeModal(${r.id})" aria-label="View recipe">${Icon.eye} View</button>
                ${r.source_type === 'manual'
            ? `<button class="btn-action" onclick="editRecipe(${r.id})" aria-label="Edit recipe">${Icon.edit} Edit</button>`
            : ''}
                <button class="btn-action danger" onclick="confirmDelete(${r.id})" aria-label="Delete recipe">${Icon.trash} Delete</button>
                <button class="btn-fav btn-icon ${isFav ? 'active' : ''}"
                        onclick="toggleFav(${r.id})"
                        aria-label="${isFav ? 'Remove from favourites' : 'Add to favourites'}"
                        aria-pressed="${isFav ? 'true' : 'false'}">
                    ${Icon.heart}
                </button>
            </div>
        </div>
    </div>`;
}

/* ══════════════════════════════════════════════════════════════
   ADD / EDIT RECIPE MODAL
══════════════════════════════════════════════════════════════ */

function openAddRecipeModal(recipe = null) {
    const editing = !!recipe;
    State.editingId = recipe ? recipe.id : null;

    const html = `<div class="modal add-recipe-modal" onclick="event.stopPropagation()" role="dialog" aria-modal="true" aria-label="${editing ? 'Edit Recipe' : 'Add New Recipe'}">
        <div class="modal-header">
            <h2><span class="icon">${editing ? Icon.edit : Icon.plus}</span>${editing ? 'Edit Recipe' : 'Add New Recipe'}</h2>
            <button class="btn-icon modal-close" onclick="closeModal()" aria-label="Close modal">${Icon.close}</button>
        </div>
        <div style="padding: 24px 32px 32px;">
            <form id="recipeForm" novalidate>
                <input type="hidden" name="id" value="${editing ? recipe.id : ''}">
                <input type="hidden" name="image_path" id="hiddenImagePath" value="${editing ? (recipe.image_path || '') : ''}">
                <input type="hidden" name="keep_image" id="keepImage" value="${editing ? '1' : ''}">

                <div class="form-group mb-4">
                    <label class="form-label" for="imageInput">Recipe Image</label>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" id="imageInput" accept="image/jpeg,image/png,image/webp" aria-label="Upload recipe image">
                        ${editing && recipe.image_path
            ? `<img src="${escHtml(recipe.image_path)}" class="upload-preview" id="uploadPreview" loading="lazy" alt="Recipe preview">`
            : `<div id="uploadPreview"></div>`}
                        <div class="upload-icon">${Icon.upload}</div>
                        <div class="upload-label">Drop image or click to browse</div>
                        <div class="upload-sub">JPG, PNG, WebP — max 5 MB</div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group full">
                        <label class="form-label" for="recipeTitle">Recipe Title *</label>
                        <input id="recipeTitle" class="form-control" name="title" placeholder="e.g. Spaghetti Carbonara" required value="${editing ? escHtml(recipe.title) : ''}">
                    </div>
                    <div class="form-group full">
                        <label class="form-label" for="recipeDesc">Description</label>
                        <textarea id="recipeDesc" class="form-control" name="description" rows="3" placeholder="Brief description of the dish...">${editing ? escHtml(recipe.description || '') : ''}</textarea>
                    </div>
                    <div class="form-group full">
                        <label class="form-label" for="recipeIngredients">Ingredients</label>
                        <textarea id="recipeIngredients" class="form-control" name="ingredients" rows="5" placeholder="One ingredient per line&#10;e.g. 200g spaghetti&#10;2 eggs">${editing ? escHtml(recipe.ingredients || '') : ''}</textarea>
                    </div>
                    <div class="form-group full">
                        <label class="form-label" for="recipeInstructions">Cooking Instructions</label>
                        <textarea id="recipeInstructions" class="form-control" name="instructions" rows="6" placeholder="Step-by-step instructions...">${editing ? escHtml(recipe.instructions || '') : ''}</textarea>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Nutrition (per serving)</label>
                        <div class="macros-row">
                            ${macroInput('calories', 'Calories', 'kcal', editing ? recipe.calories : '')}
                            ${macroInput('protein', 'Protein', 'g', editing ? recipe.protein : '')}
                            ${macroInput('carbs', 'Carbs', 'g', editing ? recipe.carbs : '')}
                            ${macroInput('fats', 'Fats', 'g', editing ? recipe.fats : '')}
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        ${Icon.save}&nbsp;${editing ? 'Save Changes' : 'Add Recipe'}
                    </button>
                </div>
            </form>
        </div>
    </div>`;

    const backdrop = document.getElementById('modalBackdrop');
    backdrop.innerHTML = html;
    backdrop.style.display = 'flex';

    initImageUpload();
    document.getElementById('recipeForm').addEventListener('submit', handleRecipeSubmit);
}

function macroInput(name, label, unit, val = '') {
    return `<div class="form-group">
        <label class="form-label">${label} <small class="text-muted">(${unit})</small></label>
        <input class="form-control" name="${name}" type="number" min="0" step="0.1" placeholder="0" value="${val || ''}" aria-label="${label} in ${unit}">
    </div>`;
}

/* ── Image Upload ─────────────────────────────────────────────── */
function initImageUpload() {
    const input = document.getElementById('imageInput');
    const zone = document.getElementById('uploadZone');
    if (!input || !zone) return;

    input.addEventListener('change', () => handleImageFile(input.files[0]));
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        handleImageFile(e.dataTransfer.files[0]);
    });
}

async function handleImageFile(file) {
    if (!file) return;
    if (!['image/jpeg', 'image/jpg', 'image/png', 'image/webp'].includes(file.type)) {
        showToast('Invalid format. Use JPG, PNG, or WebP.', 'error'); return;
    }
    if (file.size > 5 * 1024 * 1024) {
        showToast('Image must be under 5 MB.', 'error'); return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('uploadPreview');
        if (preview) preview.outerHTML = `<img src="${e.target.result}" class="upload-preview" id="uploadPreview" loading="lazy" alt="Preview">`;
    };
    reader.readAsDataURL(file);

    const fd = new FormData();
    fd.append('image', file);
    try {
        const res = await fetch('/recipes/upload-image', {
            method: 'POST',
            body: fd,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('hiddenImagePath').value = data.image_path;
            document.getElementById('keepImage').value = '';
            showToast('Image uploaded.', 'success');
        } else {
            showToast(data.message || 'Upload failed.', 'error');
        }
    } catch { showToast('Upload failed.', 'error'); }
}

/* ── Client-Side Form Validation (Member 7) ──────────────────── */
function validateRecipeForm(form) {
    let isValid = true;

    // Helper: clear previous errors
    function clearError(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.classList.remove('input-error');
        const existing = input.parentNode.querySelector('.field-error');
        if (existing) existing.remove();
    }

    // Helper: show error under a field
    function showError(inputId, message) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.classList.add('input-error');
        // Don't add duplicate errors
        if (!input.parentNode.querySelector('.field-error')) {
            const err = document.createElement('span');
            err.className = 'field-error';
            err.textContent = message;
            input.parentNode.appendChild(err);
        }
        isValid = false;
    }

    // Clear all previous errors first
    ['recipeTitle', 'recipeDesc', 'recipeIngredients',
     'recipeInstructions', 'recipeCalories', 'recipeProtein',
     'recipeCarbs', 'recipeFats'].forEach(clearError);

    // ── Rule 1: Title is required, min 3 chars, max 100 chars
    const title = document.getElementById('recipeTitle');
    if (!title) return false;
    const titleVal = title.value.trim();
    if (!titleVal) {
        showError('recipeTitle', 'Recipe title is required.');
    } else if (titleVal.length < 3) {
        showError('recipeTitle', 'Title must be at least 3 characters.');
    } else if (titleVal.length > 100) {
        showError('recipeTitle', 'Title must not exceed 100 characters.');
    }

    // ── Rule 2: Description max 500 chars (optional but if filled)
    const desc = document.getElementById('recipeDesc');
    if (desc && desc.value.trim().length > 500) {
        showError('recipeDesc', 'Description must not exceed 500 characters.');
    }

    // ── Rule 3: Ingredients max 2000 chars (optional but if filled)
    const ingredients = document.getElementById('recipeIngredients');
    if (ingredients && ingredients.value.trim().length > 2000) {
        showError('recipeIngredients', 'Ingredients must not exceed 2000 characters.');
    }

    // ── Rule 4: Instructions max 5000 chars (optional but if filled)
    const instructions = document.getElementById('recipeInstructions');
    if (instructions && instructions.value.trim().length > 5000) {
        showError('recipeInstructions', 'Instructions must not exceed 5000 characters.');
    }

    // ── Rule 5: Nutrition fields — must be non-negative numbers if filled
    const numericFields = [
        { name: 'calories', label: 'Calories', max: 10000 },
        { name: 'protein',  label: 'Protein',  max: 1000  },
        { name: 'carbs',    label: 'Carbs',    max: 1000  },
        { name: 'fats',     label: 'Fats',     max: 1000  },
    ];

    numericFields.forEach(field => {
        // The macro inputs don't have IDs in the current form, so we find by name
        const input = form.elements[field.name];
        if (!input) return;
        const val = input.value.trim();
        if (val === '') return; // optional — skip if empty

        const num = parseFloat(val);
        if (isNaN(num)) {
            // Add error directly since no ID — add class manually
            input.classList.add('input-error');
            if (!input.parentNode.querySelector('.field-error')) {
                const err = document.createElement('span');
                err.className = 'field-error';
                err.textContent = `${field.label} must be a valid number.`;
                input.parentNode.appendChild(err);
            }
            isValid = false;
        } else if (num < 0) {
            input.classList.add('input-error');
            if (!input.parentNode.querySelector('.field-error')) {
                const err = document.createElement('span');
                err.className = 'field-error';
                err.textContent = `${field.label} cannot be negative.`;
                input.parentNode.appendChild(err);
            }
            isValid = false;
        } else if (num > field.max) {
            input.classList.add('input-error');
            if (!input.parentNode.querySelector('.field-error')) {
                const err = document.createElement('span');
                err.className = 'field-error';
                err.textContent = `${field.label} seems too high (max ${field.max}).`;
                input.parentNode.appendChild(err);
            }
            isValid = false;
        }
    });

    return isValid;
}


/* ── Recipe Form Submit ───────────────────────────────────────── */
async function handleRecipeSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('submitBtn');

    // ── Run client-side validation first
    if (!validateRecipeForm(form)) {
        showToast('Please fix the errors below.', 'error');
        return; // stop here — don't submit
    }

    btn.disabled = true;
    btn.innerHTML = `<span class="spinner"></span>&nbsp;Saving…`;

    const id = form.elements['id'].value;
    const fd = new FormData();
    fd.append('action', id ? 'update_recipe' : 'add_recipe');
    if (id) fd.append('id', id);
    ['title', 'description', 'ingredients', 'instructions', 'calories', 'protein', 'carbs', 'fats'].forEach(k => {
        fd.append(k, form.elements[k]?.value || '');
    });
    fd.append('image_path', form.elements['image_path'].value);
    if (form.elements['keep_image'].value) fd.append('keep_image', '1');

    try {
        const res = await dbPost(fd);
        if (res.success) {
            showToast(res.message, 'success');
            if (id) {
                const idx = State.recipes.findIndex(r => r.id == id);
                if (idx > -1) State.recipes[idx] = res.data; else State.recipes.unshift(res.data);
            } else {
                State.recipes.unshift(res.data);
            }
            closeModal();
            updateRecipesView('all');
            updateFavoritesView();
        } else {
            showToast(res.message || 'Failed to save recipe.', 'error');
            btn.disabled = false;
            btn.innerHTML = `${Icon.save}&nbsp;Save`;
        }
    } catch {
        showToast('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = `${Icon.save}&nbsp;Save`;
    }
}

/* ── Handle Laravel validation error responses (M7) ─────────── */
function handleLaravelErrors(responseData) {
    // Laravel returns errors in this format:
    // { "errors": { "title": ["The title field is required."], "calories": ["..."] } }
    // OR { "message": "Some error" }

    if (!responseData) return false;

    // Single message error (non-validation)
    if (responseData.message && !responseData.errors) {
        showToast(responseData.message, 'error');
        return true;
    }

    // Field-level validation errors from Laravel
    if (responseData.errors) {
        // Map Laravel field names to our input IDs
        const fieldMap = {
            'title':        'recipeTitle',
            'description':  'recipeDesc',
            'ingredients':  'recipeIngredients',
            'instructions': 'recipeInstructions',
            'calories':     null, 
            'protein':      null,
            'carbs':        null,
            'fats':         null,
        };

        let firstField = null;

        Object.entries(responseData.errors).forEach(([field, messages]) => {
            const inputId = fieldMap[field];
            const input = inputId
                ? document.getElementById(inputId)
                : document.querySelector(`[name="${field}"]`);

            if (input) {
                // Highlight field
                input.classList.add('input-error');

                // Show error message under field
                if (!input.parentNode.querySelector('.field-error')) {
                    const err = document.createElement('span');
                    err.className = 'field-error';
                    err.textContent = messages[0]; // show first error message
                    input.parentNode.appendChild(err);
                }

                // Remember first error field to scroll to it
                if (!firstField) firstField = input;
            }
        });

        // Scroll to first error field so user sees it
        if (firstField) {
            firstField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstField.focus();
        }

        showToast('Please fix the errors below.', 'error');
        return true; // errors were handled
    }

    return false; // no errors found
}

/* ══════════════════════════════════════════════════════════════
   API SEARCH WITH PAGINATION
══════════════════════════════════════════════════════════════ */

async function doApiSearch(page = 1) {
    const input = document.getElementById('apiSearchInput');
    const modeEl = document.getElementById('apiSearchMode');
    const query = (page === 1 ? input?.value?.trim() : State.lastQuery) || '';
    const mode = (page === 1 ? modeEl?.value : State.lastMode) || 'name';

    if (!query) {
        const input = document.getElementById('apiSearchInput');
        if (input) {
            input.classList.add('input-error');
            input.focus();
            setTimeout(() => input.classList.remove('input-error'), 2000);
        }
        showToast('Please enter a search term.', 'info');
        return;
    }

    // Persist for page changes
    State.lastQuery = query;
    State.lastMode = mode;
    State.currentPage = page;

    const results = document.getElementById('apiResults');
    const btn = document.getElementById('apiSearchBtn');
    results.innerHTML = `<div class="loading-wrap"><span class="spinner"></span> Searching…</div>`;
    if (btn) btn.disabled = true;

    try {
        const res = await ApiOps.searchRecipes(query, mode, page);
        if (btn) btn.disabled = false;

        if (!res.success) {
            showToast(res.message || 'Search failed.', 'error');
            results.innerHTML = '';
            return;
        }

        const items = res.data?.results || [];
        State.totalResults = res.data?.totalResults || 0;
        State.totalPages = res.data?.totalPages || 1;
        State.currentPage = res.data?.currentPage || page;

        if (!items.length) {
            results.innerHTML = emptyState('No results found', `Try different keywords for "${escHtml(query)}".`);
            return;
        }

        const start = (State.currentPage - 1) * State.perPage + 1;
        const end = Math.min(State.currentPage * State.perPage, State.totalResults);

        results.innerHTML = `
            <p class="results-meta">
                Showing <strong>${start}–${end}</strong> of <strong>${State.totalResults}</strong> results for "<strong>${escHtml(query)}</strong>"
            </p>
            <div class="recipes-grid" id="apiGrid">
                ${items.map(apiCard).join('')}
            </div>
            ${buildPagination(State.currentPage, State.totalPages)}
        `;

        // Scroll to results
        results.scrollIntoView({ behavior: 'smooth', block: 'start' });

    } catch {
        if (btn) btn.disabled = false;
        showToast('Search failed. Check your API key or network.', 'error');
        results.innerHTML = '';
    }
}

/* ── Pagination builder ───────────────────────────────────────── */
function buildPagination(current, total) {
    if (total <= 1) return '';

    const pages = [];
    const delta = 2;

    // Always include first, last, and pages around current
    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
            pages.push(i);
        }
    }

    // Deduplicate and sort
    const unique = [...new Set(pages)].sort((a, b) => a - b);

    let html = '<nav class="pagination" role="navigation" aria-label="Search results pagination">';

    // Prev
    html += `<button class="page-btn" onclick="doApiSearch(${current - 1})"
        ${current <= 1 ? 'disabled aria-disabled="true"' : ''}
        aria-label="Previous page">${Icon.prev} Prev</button>`;

    // Page numbers with ellipsis
    let lastPage = 0;
    for (const p of unique) {
        if (lastPage && p - lastPage > 1) {
            html += `<span class="page-ellipsis" aria-hidden="true">…</span>`;
        }
        html += `<button class="page-btn ${p === current ? 'active' : ''}"
            onclick="doApiSearch(${p})"
            aria-label="Page ${p}"
            ${p === current ? 'aria-current="page"' : ''}>${p}</button>`;
        lastPage = p;
    }

    // Next
    html += `<button class="page-btn" onclick="doApiSearch(${current + 1})"
        ${current >= total ? 'disabled aria-disabled="true"' : ''}
        aria-label="Next page">Next ${Icon.next}</button>`;

    html += '</nav>';
    return html;
}

/* ── API Card ─────────────────────────────────────────────────── */
function apiCard(r) {
    const imgHtml = r.image
        ? `<img src="${escHtml(r.image)}" alt="${escHtml(r.title)}" loading="lazy">`
        : `<div class="recipe-img-placeholder">${Icon.image}</div>`;

    return `<div class="recipe-card api-card">
        <div class="recipe-img-wrap" onclick="openApiDetail(${r.id})" role="button" tabindex="0" aria-label="View ${escHtml(r.title)}">
            ${imgHtml}
            <span class="recipe-badge api-badge">Spoonacular</span>
        </div>
        <div class="recipe-body">
            <div class="recipe-title" onclick="openApiDetail(${r.id})" role="button" tabindex="0">${escHtml(r.title)}</div>
            <div class="recipe-macros">
                <div class="macro-item"><div class="macro-val">${fmt(r.calories)}</div><div class="macro-key">kcal</div></div>
                <div class="macro-item"><div class="macro-val">${fmt(r.protein)}g</div><div class="macro-key">Protein</div></div>
                <div class="macro-item"><div class="macro-val">${fmt(r.carbs)}g</div><div class="macro-key">Carbs</div></div>
                <div class="macro-item"><div class="macro-val">${fmt(r.fats)}g</div><div class="macro-key">Fats</div></div>
            </div>
            <div style="display:flex;align-items:center;gap:6px;color:var(--text-muted);font-size:.8rem;margin-bottom:12px;">
                ${Icon.clock}&nbsp;${r.readyInMinutes || '—'} min
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
                <button class="btn btn-sm btn-outline" onclick="openApiDetail(${r.id})">${Icon.eye}&nbsp;View</button>
                <button class="btn btn-sm btn-primary" onclick="saveApiRecipe(${r.id}, this, 0)" title="Save to My Recipes" aria-label="Save to My Recipes">${Icon.save}</button>
                <button class="btn btn-sm btn-accent"  onclick="saveApiRecipe(${r.id}, this, 1)" title="Add to Favourites" aria-label="Add to Favourites">${Icon.heart}</button>
            </div>
        </div>
    </div>`;
}

/* ══════════════════════════════════════════════════════════════
   MODALS
══════════════════════════════════════════════════════════════ */

async function openApiDetail(recipeId) {
    alert('openApiDetail called with: ' + recipeId); // temporary test
    showLoadingModal();
    try {
        const res = await ApiOps.getRecipeDetail(recipeId);
        if (!res.success) { 
            closeModal(); 
            showToast(res.message, 'error'); 
            return; 
        }

        // Check if already saved
        const alreadySaved = !!State.recipes.find(r => r.api_recipe_id == recipeId);
        // DEBUGGING LINES
        console.log('alreadySaved:', alreadySaved);
        console.log('State.recipes:', State.recipes.map(r => r.api_recipe_id));
        
        
        const isFav = !!State.recipes.find(r => r.api_recipe_id == recipeId && +r.is_favorite);

        renderDetailModal(res.data, true, isFav, alreadySaved);
    } catch (e) { console.error(e);
        closeModal(); 
        showToast('Failed to load recipe details.', 'error'); 
    }
}

async function openRecipeModal(id) {
    showLoadingModal();
    try {
        const fd = new FormData();
        fd.append('action', 'get_recipe');
        fd.append('id', id);
        const res = await dbPost(fd);
        if (!res.success) { closeModal(); showToast(res.message, 'error'); return; }


        const found = State.recipes.find(r => r.id == id);
        console.log('Recipe found in state:', found);        // check this
        console.log('is_favorite value:', found?.is_favorite); // check this

        const isFav = !!State.recipes.find(r => r.id == id)?.is_favorite;
        const isInCollection = !!State.recipes.find(r => r.id == id && r.source_type === 'api');
        renderDetailModal(res.data, false, isFav, isInCollection);

    } catch (e) { 
        console.error(e);
        closeModal(); 
        showToast('Failed to load recipe.', 'error'); 
    }
}

function renderDetailModal(r, isApi, isFav = false, isInCollection = false) {
    const imgHtml = (r.image_path || r.image)
        ? `<img src="${escHtml(r.image_path || r.image)}" alt="${escHtml(r.title)}" loading="lazy">`
        : `<div class="modal-hero-placeholder">${Icon.image}</div>`;

    const ingredientsHtml = r.ingredients
        ? `<ul class="ingredients-list">
               ${(r.ingredients || '').split('\n').filter(Boolean)
            .map(i => `<li>${escHtml(i.trim())}</li>`).join('')}
           </ul>`
        : `<p class="text-muted">No ingredients listed.</p>`;

    const instructionsHtml = r.instructions
        ? `<ol class="instructions-list">
               ${(r.instructions || '').split('\n').filter(Boolean).map((s, i) => {
            const clean = s.replace(/^\d+\.\s*/, '');
            return `<li><span class="step-num">${i + 1}</span><span>${escHtml(clean.trim())}</span></li>`;
        }).join('')}
           </ol>`
        : `<p class="text-muted">No instructions provided.</p>`;

    const actionBtns = isApi
    ? `
        <button class="btn ${isInCollection ? 'btn-outline' : 'btn-primary'}"
                onclick="saveApiRecipe(${r.id}, this, 0)"
                ${isInCollection ? 'disabled' : ''}>
            ${Icon.save}&nbsp;${isInCollection ? '✓ Saved' : 'Add to Collection'}
        </button>

        <button class="btn-fav btn-icon ${isFav ? 'active' : ''}"
                onclick="toggleFav(${r.id})">
            ${Icon.heart}
        </button>
    `
    : `
        <button class="btn-fav btn-icon ${isFav ? 'active' : ''}"
                onclick="toggleFav(${r.id})">
            ${Icon.heart}
        </button>

        <button class="btn ${isInCollection ? 'btn-outline' : 'btn-primary'}"
                onclick="saveApiRecipe(${r.id}, this, 0)"
                ${isInCollection ? 'disabled' : ''}>
            ${isInCollection ? '✓ Saved' : 'Add to Collection'}
        </button>

        ${r.source_type === 'manual'
            ? `<button class="btn btn-primary btn-sm" onclick="closeModal();editRecipe(${r.id})">
                ${Icon.edit}&nbsp;Edit Recipe
            </button>`
            : ''}
    `;

    const backdrop = document.getElementById('modalBackdrop');
    backdrop.innerHTML = `<div class="modal" onclick="event.stopPropagation()" role="dialog" aria-modal="true" aria-label="${escHtml(r.title)}">
        <div class="modal-hero">
            ${imgHtml}
            <button class="modal-close" onclick="closeModal()" aria-label="Close modal">${Icon.close}</button>
        </div>
        <div class="modal-body">
            <div class="modal-title">${escHtml(r.title)}</div>
            <div class="modal-meta">
                ${r.readyInMinutes ? `<span>${Icon.clock}&nbsp;${r.readyInMinutes} min</span>` : ''}
                ${r.servings ? `<span>${Icon.utensils}&nbsp;${r.servings} servings</span>` : ''}
                ${isApi ? `<span style="color:var(--accent)">${Icon.star}&nbsp;Spoonacular</span>` : ''}
            </div>
            <div class="nutrition-grid">
                ${nutritionCard(r.calories, 'kcal', 'Calories')}
                ${nutritionCard(r.protein, 'g', 'Protein')}
                ${nutritionCard(r.carbs, 'g', 'Carbs')}
                ${nutritionCard(r.fats, 'g', 'Fats')}
            </div>
            ${r.description
            ? `<div class="detail-section"><p class="text-secondary" style="font-size:.9rem;line-height:1.6;">${escHtml(r.description).slice(0, 420)}${r.description.length > 420 ? '…' : ''}</p></div>`
            : ''}
            <div class="divider"></div>
            <div class="detail-section">
                <h4><span class="icon">${Icon.utensils}</span>Ingredients</h4>
                ${ingredientsHtml}
            </div>
            <div class="detail-section">
                <h4><span class="icon">${Icon.book}</span>Instructions</h4>
                ${instructionsHtml}
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:24px;">
                ${actionBtns}
            </div>
        </div>
    </div>`;
    backdrop.style.display = 'flex';
}

function nutritionCard(val, unit, label) {
    return `<div class="nutrition-card">
        <div class="nutrition-val">${fmt(val)}<span class="nutrition-unit">&nbsp;${unit}</span></div>
        <div class="nutrition-label">${label}</div>
    </div>`;
}

function showLoadingModal() {
    const backdrop = document.getElementById('modalBackdrop');
    backdrop.innerHTML = `<div class="modal" style="padding:60px;text-align:center;max-width:300px;" role="status">
        <span class="spinner"></span>
        <p style="margin-top:16px;color:var(--text-muted);">Loading…</p>
    </div>`;
    backdrop.style.display = 'flex';
}

function closeModal() {
    const b = document.getElementById('modalBackdrop');
    if (b) { b.style.display = 'none'; b.innerHTML = ''; }
}

/* ── Delete confirmation ─────────────────────────────────────── */
function confirmDelete(id) {
    const backdrop = document.getElementById('modalBackdrop');
    backdrop.innerHTML = `<div class="modal confirm-modal" onclick="event.stopPropagation()" style="max-width:440px" role="alertdialog" aria-modal="true">
        <div class="modal-body" style="padding:44px 40px;text-align:center;">
            <div class="confirm-icon">${Icon.trash}</div>
            <h3>Delete Recipe?</h3>
            <p>This action cannot be undone. The recipe and its image will be permanently removed.</p>
            <div class="confirm-actions">
                <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger"  onclick="deleteRecipe(${id})">Delete</button>
            </div>
        </div>
    </div>`;
    backdrop.style.display = 'flex';
}

/* ══════════════════════════════════════════════════════════════
   CRUD
══════════════════════════════════════════════════════════════ */

async function deleteRecipe(id) {
    closeModal();
    const fd = new FormData();
    fd.append('action', 'delete_recipe');
    fd.append('id', id);
    try {
        const res = await dbPost(fd);
        if (res.success) {
            State.recipes = State.recipes.filter(r => r.id != id);
            showToast('Recipe deleted.', 'success');
            updateRecipesView('all');
            updateFavoritesView();
        } else { showToast(res.message, 'error'); }
    } catch { showToast('Delete failed.', 'error'); }
}

async function toggleFav(id) {
    const fd = new FormData();
    fd.append('action', 'toggle_favorite');
    fd.append('id', id);
    try {
        const res = await dbPost(fd);
        if (res.success) {
            // Update just the is_favorite field in local state
            const idx = State.recipes.findIndex(r => r.id == id);
            if (idx > -1) {
                State.recipes[idx].is_favorite = res.is_favorite ? 1 : 0;
            }

            const isFav = !!res.is_favorite;
            showToast(res.message || (isFav ? 'Added to favourites.' : 'Removed from favourites.'), 'success');

            // Update heart button immediately
            const heartBtn = document.querySelector(`button[onclick="toggleFav(${id})"]`);
            if (heartBtn) {
                heartBtn.classList.toggle('active', isFav);
            }

            updateRecipesView();
            updateFavoritesView();
        }
    } catch { 
        showToast('Failed to update favourite.', 'error'); 
    }
}

function editRecipe(id) {
    const recipe = State.recipes.find(r => r.id == id);
    if (!recipe) { showToast('Recipe not found in local state.', 'error'); return; }
    openAddRecipeModal(recipe);
}

async function saveApiRecipe(recipeId, btn, isFavorite = 0) {
    if (btn) { btn.disabled = true; btn.innerHTML = `<span class="spinner" style="width:14px;height:14px;border-width:2px;"></span>`; }
    try {
        const detailRes = await ApiOps.getRecipeDetail(recipeId);
        if (!detailRes.success) {
            showToast(detailRes.message, 'error');
            if (btn) { btn.disabled = false; btn.innerHTML = isFavorite ? Icon.heart : Icon.save; }
            return;
        }
        const saveRes = await ApiOps.saveApiRecipe(detailRes.data, isFavorite);
        if (saveRes.success) {
            State.recipes.unshift(saveRes.data);
            showToast(isFavorite ? 'Added to your Favourites!' : 'Saved to your collection!', 'success');
            updateRecipesView('all');
            updateFavoritesView();
            if (btn) { btn.disabled = true; btn.innerHTML = `${Icon.check}`; }
        } else {
            // 409 means already saved — not a real error
            if (saveRes.message?.includes('already saved')) {
                showToast('Already in your collection!', 'info');
                if (btn) { btn.disabled = true; btn.innerHTML = `${Icon.check}&nbsp;Saved`; }
            } else {
                showToast(saveRes.message || 'Could not save recipe.', 'error');
                if (btn) { btn.disabled = false; btn.innerHTML = isFavorite ? `${Icon.heart}` : `${Icon.save}`; }
            }
        }
    } catch {
        showToast('Failed to save recipe.', 'error');
        if (btn) { btn.disabled = false; btn.innerHTML = isFavorite ? Icon.heart : Icon.save; }
    }
}

/* ── Helpers ──────────────────────────────────────────────────── */
function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = String(str || '');
    return d.innerHTML;
}
function fmt(n) {
    const v = parseFloat(n);
    return isNaN(v) ? '0' : (v % 1 === 0 ? String(v) : v.toFixed(1));
}
function skeletonCards(n) {
    return Array(n).fill(`<div class="skeleton-card">
        <div class="skeleton skel-img"></div>
        <div class="skeleton skel-line medium"></div>
        <div class="skeleton skel-line short"></div>
    </div>`).join('');
}
function emptyState(title, sub) {
    return `<div class="empty-state">
        ${Icon.empty}
        <h3>${title}</h3>
        <p>${sub}</p>
    </div>`;
}

/* ── Init ─────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    initTheme();

    document.getElementById('themeToggle')
        ?.addEventListener('click', toggleTheme);

    document.getElementById('modalBackdrop')
        ?.addEventListener('click', closeModal);

    // FAB
    const fab = document.getElementById('fabAdd');
    if (fab) {
        fab.innerHTML = Icon.plus;
        fab.addEventListener('click', () => openAddRecipeModal());
    }

    // Keyboard: close modal on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });

    initAllSections();
    initScrollObserver();
});