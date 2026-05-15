@extends('layouts.app')

@section('content')

<main class="app-main" role="main">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success" style="background:rgba(46,204,113,0.15);border:1px solid #2ecc71;color:#2ecc71;padding:12px 20px;border-radius:10px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <section class="page-section">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <h2 class="section-title" style="margin:0">My Recipes</h2>
            <a href="{{ route('recipes.create') }}" class="btn btn-primary" style="border-radius:30px;padding:10px 22px;">
                + New Recipe
            </a>
        </div>

        @if($recipes->isEmpty())
            <p style="color:var(--text-muted);text-align:center;padding:40px 0;">
                No recipes yet. <a href="{{ route('recipes.create') }}">Create your first one!</a>
            </p>
        @else
            <div class="recipes-grid">
                @foreach($recipes as $recipe)
                    <div class="recipe-card" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;">
                        @if($recipe->image_path)
                            <img src="{{ $recipe->image_path }}" alt="{{ $recipe->title }}"
                                 style="width:100%;height:180px;object-fit:cover;">
                        @else
                            <div style="width:100%;height:180px;background:var(--surface-hover);display:flex;align-items:center;justify-content:center;font-size:3rem;">🍽️</div>
                        @endif

                        <div style="padding:16px;">
                            <h3 style="margin-bottom:6px;font-size:1.1rem;">{{ $recipe->title }}</h3>
                            <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:12px;line-height:1.5;">
                                {{ Str::limit($recipe->description, 80) }}
                            </p>

                            {{-- Nutrition row --}}
                            <div style="display:flex;gap:10px;font-size:0.78rem;color:var(--text-muted);margin-bottom:14px;">
                                <span>🔥 {{ $recipe->calories }} kcal</span>
                                <span>💪 {{ $recipe->protein }}g</span>
                                <span>🌾 {{ $recipe->carbs }}g</span>
                                <span>🧈 {{ $recipe->fats }}g</span>
                            </div>

                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('recipes.show', $recipe) }}"
                                   class="btn btn-primary" style="flex:1;text-align:center;padding:8px;border-radius:8px;font-size:0.85rem;">
                                    View
                                </a>
                                <a href="{{ route('recipes.edit', $recipe) }}"
                                   style="flex:1;text-align:center;padding:8px;border-radius:8px;font-size:0.85rem;background:var(--surface-hover);color:var(--text);text-decoration:none;border:1px solid var(--border);">
                                    Edit
                                </a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                                      onsubmit="return confirm('Delete this recipe?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="padding:8px 12px;border-radius:8px;font-size:0.85rem;background:rgba(231,76,60,0.1);color:#e74c3c;border:1px solid rgba(231,76,60,0.3);cursor:pointer;">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</main>

@endsection
