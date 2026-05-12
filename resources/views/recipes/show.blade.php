@extends('layouts.app')

@section('content')

<main class="app-main" role="main">
    <section class="page-section" style="max-width:750px;margin:0 auto;">

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="background:rgba(46,204,113,0.15);border:1px solid #2ecc71;color:#2ecc71;padding:12px 20px;border-radius:10px;margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Back link --}}
        <a href="{{ route('recipes.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.9rem;">
            ← Back to Recipes
        </a>

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;margin-top:16px;">

            {{-- Hero image --}}
            @if($recipe->image_path)
                <img src="{{ $recipe->image_path }}" alt="{{ $recipe->title }}"
                     style="width:100%;height:280px;object-fit:cover;">
            @else
                <div style="width:100%;height:200px;background:var(--surface-hover);display:flex;align-items:center;justify-content:center;font-size:4rem;">🍽️</div>
            @endif

            <div style="padding:28px;">

                {{-- Header row --}}
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:16px;flex-wrap:wrap;">
                    <div>
                        <h1 style="font-size:1.6rem;margin-bottom:6px;">{{ $recipe->title }}</h1>
                        <span style="font-size:0.8rem;padding:4px 10px;border-radius:20px;
                              {{ $recipe->source_type === 'api' ? 'background:rgba(52,152,219,0.15);color:#3498db;border:1px solid rgba(52,152,219,0.3)' : 'background:rgba(46,204,113,0.15);color:#2ecc71;border:1px solid rgba(46,204,113,0.3)' }}">
                            {{ $recipe->source_type === 'api' ? '🌐 API Recipe' : '✍️ My Recipe' }}
                        </span>
                    </div>

                    {{-- Favorite button --}}
                    <button id="favBtn"
                            onclick="toggleFav({{ $recipe->id }})"
                            style="padding:10px 18px;border-radius:30px;border:1px solid var(--border);background:var(--surface-hover);cursor:pointer;font-size:1.2rem;"
                            title="{{ $recipe->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}">
                        {{ $recipe->is_favorite ? '❤️' : '🤍' }}
                    </button>
                </div>

                {{-- Description --}}
                @if($recipe->description)
                    <p style="color:var(--text-muted);line-height:1.7;margin-bottom:24px;">
                        {{ $recipe->description }}
                    </p>
                @endif

                {{-- Nutrition grid --}}
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:28px;">
                    @foreach([['🔥','Calories', $recipe->calories, 'kcal'],['💪','Protein', $recipe->protein, 'g'],['🌾','Carbs', $recipe->carbs, 'g'],['🧈','Fats', $recipe->fats, 'g']] as [$icon,$label,$val,$unit])
                    <div style="text-align:center;background:var(--surface-hover);border-radius:12px;padding:14px;">
                        <div style="font-size:1.5rem;margin-bottom:4px;">{{ $icon }}</div>
                        <div style="font-size:1.1rem;font-weight:700;">{{ $val }}{{ $unit }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Ingredients --}}
                <div style="margin-bottom:24px;">
                    <h3 style="margin-bottom:12px;font-size:1.1rem;">🥕 Ingredients</h3>
                    <div style="background:var(--surface-hover);border-radius:12px;padding:16px;line-height:1.8;white-space:pre-line;color:var(--text-muted);">
                        {{ $recipe->ingredients }}
                    </div>
                </div>

                {{-- Instructions --}}
                <div style="margin-bottom:28px;">
                    <h3 style="margin-bottom:12px;font-size:1.1rem;">📋 Instructions</h3>
                    <div style="background:var(--surface-hover);border-radius:12px;padding:16px;line-height:1.8;white-space:pre-line;color:var(--text-muted);">
                        {{ $recipe->instructions }}
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-primary"
                       style="padding:10px 24px;border-radius:10px;">
                        ✏️ Edit Recipe
                    </a>
                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this recipe?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="padding:10px 24px;border-radius:10px;background:rgba(231,76,60,0.1);color:#e74c3c;border:1px solid rgba(231,76,60,0.3);cursor:pointer;">
                            🗑️ Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>
</main>

<script>
// AJAX favorite toggle — works with M7 JS (T7)
async function toggleFav(id) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch(`/recipes/${id}/favorite`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('favBtn').textContent = data.is_favorite ? '❤️' : '🤍';
        }
    } catch (err) {
        console.error('Could not toggle favorite:', err);
    }
}
</script>

@endsection
