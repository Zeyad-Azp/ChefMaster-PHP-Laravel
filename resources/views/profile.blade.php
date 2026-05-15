@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="profile-page">

        {{-- Profile Hero --}}
        <div class="profile-hero">
            <div class="profile-avatar-large">
                @if($user->avatar_path)
                    <img src="{{ $user->avatar_path }}" alt="{{ $user->fullname }}'s avatar">
                @else
                    <span class="profile-avatar-initials">{{ strtoupper(substr($user->fullname, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->fullname)[1] ?? '', 0, 1)) }}</span>
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $user->fullname }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
                <p class="profile-joined">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Member since {{ $user->created_at->format('F Y') }}
                </p>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="auth-alert-success" style="margin-bottom:24px;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="auth-alert-error" style="margin-bottom:24px;">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Edit Card --}}
        <div class="profile-edit-card">
            <h2>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </span>
                Edit Profile
            </h2>

            {{-- Avatar Upload --}}
            <div class="avatar-upload-group" style="margin-bottom:28px;">
                <label class="avatar-label-text">Profile Photo</label>
                <div class="avatar-upload-wrap" id="profileAvatarWrap">
                    <input type="file" id="profileAvatarInput" accept="image/jpeg,image/png,image/webp">
                    <div class="avatar-preview" id="profileAvatarPreview">
                        @if($user->avatar_path)
                            <img src="{{ $user->avatar_path }}" alt="Avatar">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        @endif
                    </div>
                    <div class="avatar-overlay">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </div>
                </div>
                <span class="avatar-hint">Click to change — JPG, PNG or WebP — max 2 MB</span>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="profile-form-grid">
                    {{-- Fullname --}}
                    <div class="auth-form-group">
                        <label for="profileFullname">Full Name</label>
                        <input type="text" name="fullname" id="profileFullname"
                               value="{{ old('fullname', $user->fullname) }}"
                               class="auth-input {{ $errors->has('fullname') ? 'input-error' : '' }}"
                               placeholder="Your full name">
                        @error('fullname')
                            <span class="auth-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="auth-form-group">
                        <label for="profileEmail">Email</label>
                        <input type="email" name="email" id="profileEmail"
                               value="{{ old('email', $user->email) }}"
                               class="auth-input {{ $errors->has('email') ? 'input-error' : '' }}"
                               placeholder="you@example.com">
                        @error('email')
                            <span class="auth-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="auth-form-group">
                        <label for="profilePassword">New Password <small style="color:var(--text-muted);font-weight:400;">(optional)</small></label>
                        <input type="password" name="password" id="profilePassword"
                               class="auth-input {{ $errors->has('password') ? 'input-error' : '' }}"
                               placeholder="Leave blank to keep current">
                        @error('password')
                            <span class="auth-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="auth-form-group">
                        <label for="profilePasswordConfirm">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="profilePasswordConfirm"
                               class="auth-input"
                               placeholder="Repeat new password">
                    </div>
                </div>

                <div style="margin-top:28px;display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
                    <a href="{{ route('home') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<script>
    // AJAX avatar upload from profile page
    document.getElementById('profileAvatarInput')?.addEventListener('change', async function() {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) { alert('Image must be under 2 MB.'); this.value = ''; return; }

        // Preview
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('profileAvatarPreview').innerHTML =
                `<img src="${e.target.result}" alt="Avatar preview">`;
        };
        reader.readAsDataURL(file);

        // Upload
        const fd = new FormData();
        fd.append('avatar', file);
        try {
            const res = await fetch('{{ route("profile.avatar") }}', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                }
            });
            const data = await res.json();
            if (data.success) {
                // Update the hero avatar too
                const hero = document.querySelector('.profile-avatar-large');
                if (hero) hero.innerHTML = `<img src="${data.avatar_path}" alt="Avatar">`;
            }
        } catch (e) { console.error('Avatar upload failed:', e); }
    });
</script>
@endsection
