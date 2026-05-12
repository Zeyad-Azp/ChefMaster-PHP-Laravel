@extends('layouts.app')

@section('content')

<main class="app-main" role="main">
    <section class="page-section" style="max-width:700px;margin:0 auto;">

        <div style="margin-bottom:24px;">
            <a href="{{ route('recipes.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.9rem;">
                ← Back to Recipes
            </a>
            <h2 class="section-title" style="margin-top:12px;">Create New Recipe</h2>
        </div>

        {{-- Validation errors — M5 (T5) will enhance this with @error directives --}}
        @if($errors->any())
            <div style="background:rgba(231,76,60,0.1);border:1px solid rgba(231,76,60,0.3);color:#e74c3c;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
                <strong>Please fix the following errors:</strong>
                <ul style="margin:8px 0 0 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('recipes.store') }}" method="POST"
              style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;">
            @csrf

            {{-- Title --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">
                    Recipe Title <span style="color:#e74c3c">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="e.g. Spaghetti Carbonara"
                       style="width:100%;padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:0.95rem;"
                       required>
                @error('title')
                    <span style="color:#e74c3c;font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Description</label>
                <textarea name="description" rows="3"
                          placeholder="Brief description of your recipe..."
                          style="width:100%;padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:0.95rem;resize:vertical;">{{ old('description') }}</textarea>
                @error('description')
                    <span style="color:#e74c3c;font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Ingredients --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">
                    Ingredients <span style="color:#e74c3c">*</span>
                </label>
                <textarea name="ingredients" rows="5"
                          placeholder="List ingredients, one per line..."
                          style="width:100%;padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:0.95rem;resize:vertical;"
                          required>{{ old('ingredients') }}</textarea>
                @error('ingredients')
                    <span style="color:#e74c3c;font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Instructions --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">
                    Instructions <span style="color:#e74c3c">*</span>
                </label>
                <textarea name="instructions" rows="6"
                          placeholder="Step-by-step cooking instructions..."
                          style="width:100%;padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:0.95rem;resize:vertical;"
                          required>{{ old('instructions') }}</textarea>
                @error('instructions')
                    <span style="color:#e74c3c;font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Nutrition (4 columns) --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;margin-bottom:10px;font-weight:600;font-size:0.9rem;">Nutrition Info (optional)</label>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
                    <div>
                        <label style="font-size:0.8rem;color:var(--text-muted);display:block;margin-bottom:4px;">Calories (kcal)</label>
                        <input type="number" name="calories" value="{{ old('calories', 0) }}" min="0" step="0.1"
                               style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);">
                        @error('calories')<span style="color:#e74c3c;font-size:0.75rem;">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label style="font-size:0.8rem;color:var(--text-muted);display:block;margin-bottom:4px;">Protein (g)</label>
                        <input type="number" name="protein" value="{{ old('protein', 0) }}" min="0" step="0.1"
                               style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);">
                        @error('protein')<span style="color:#e74c3c;font-size:0.75rem;">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label style="font-size:0.8rem;color:var(--text-muted);display:block;margin-bottom:4px;">Carbs (g)</label>
                        <input type="number" name="carbs" value="{{ old('carbs', 0) }}" min="0" step="0.1"
                               style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);">
                        @error('carbs')<span style="color:#e74c3c;font-size:0.75rem;">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label style="font-size:0.8rem;color:var(--text-muted);display:block;margin-bottom:4px;">Fats (g)</label>
                        <input type="number" name="fats" value="{{ old('fats', 0) }}" min="0" step="0.1"
                               style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);">
                        @error('fats')<span style="color:#e74c3c;font-size:0.75rem;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Image URL --}}
            <div style="margin-bottom:24px;">
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:0.9rem;">Image URL (optional)</label>
                <input type="text" name="image_path" value="{{ old('image_path') }}"
                       placeholder="https://example.com/image.jpg"
                       style="width:100%;padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:0.95rem;">
                @error('image_path')
                    <span style="color:#e74c3c;font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            {{-- Submit --}}
            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary"
                        style="flex:1;padding:12px;border-radius:10px;font-size:1rem;font-weight:600;">
                    Save Recipe
                </button>
                <a href="{{ route('recipes.index') }}"
                   style="flex:1;text-align:center;padding:12px;border-radius:10px;font-size:1rem;background:var(--surface-hover);color:var(--text);text-decoration:none;border:1px solid var(--border);">
                    Cancel
                </a>
            </div>

        </form>
    </section>
</main>

@endsection
