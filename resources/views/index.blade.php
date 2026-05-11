@extends('layouts.app')

@section('content')

<main class="app-main" id="appMain" role="main" aria-label="Main Content">

    <section class="page-section" id="sectionDiscover" style="padding-top: 20px;">
        <div class="section-hero" style="margin-bottom: 40px; text-align: center;">
            <h1 style="margin-bottom: 15px;">Welcome to <em>ChefMaster</em></h1>
            <p style="margin-bottom: 25px; color: var(--text-muted);">Your intelligent culinary companion — search online, create your own, and save your favourites.</p>
            <button class="btn btn-primary" onclick="openAddRecipeModal()" style="font-size: 1.1rem; padding: 12px 24px; border-radius: 30px; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;vertical-align:middle;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Create New Recipe
            </button>
        </div>
        <h2 class="section-title" id="discoverTitle"></h2>
        <div class="search-wrap" id="searchWrap"></div>
        <div id="apiResults"></div>
    </section>

    <section class="page-section" id="sectionRecipes">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <h2 class="section-title" style="margin:0" id="recipesTitle">My Recipes</h2>
        </div>
        <div class="filter-bar" id="filterBar"></div>
        <div id="recipesGrid" class="recipes-grid"></div>
    </section>

    <section class="page-section" id="sectionFavorites" style="background: var(--surface-hover); border-radius: 20px; padding: 30px; margin-top: 40px;">
        <h2 class="section-title" id="favTitle" style="color: var(--accent);"></h2>
        <p style="color: var(--text-muted); margin-bottom: 25px;">Your personal collection of hand-picked favorites.</p>
        <div id="favGrid" class="recipes-grid"></div>
    </section>

</main>

<button class="fab-add" id="fabAdd" title="Add New Recipe" aria-label="Add new recipe"></button>

@endsection