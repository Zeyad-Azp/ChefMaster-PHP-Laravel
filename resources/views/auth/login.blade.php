@extends('layouts.app')

@section('content')
<main class="app-main auth-page" role="main">
    <div class="auth-card">

        {{-- Header --}}
        <div class="auth-header">
            <div class="auth-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                    <line x1="6" x2="18" y1="17" y2="17"/>
                </svg>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to your ChefMaster account</p>
        </div>

        {{-- Flash success --}}
        @if(session('success'))
            <div class="auth-alert-success">{{ session('success') }}</div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="auth-alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="auth-form-wrapper">
            @csrf

            {{-- Email --}}
            <div class="auth-form-group">
                <label for="loginEmail">Email Address</label>
                <input type="email" name="email" id="loginEmail" value="{{ old('email') }}"
                       placeholder="you@example.com" autocomplete="email"
                       class="auth-input {{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')
                    <span class="auth-field-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="auth-form-group">
                <label for="loginPassword">Password</label>
                <input type="password" name="password" id="loginPassword"
                       placeholder="Enter your password" autocomplete="current-password"
                       class="auth-input {{ $errors->has('password') ? 'input-error' : '' }}">
                @error('password')
                    <span class="auth-field-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="auth-remember-row">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="auth-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Sign In
            </button>
        </form>

        {{-- Register link --}}
        <p class="auth-footer-text">
            Don't have an account?
            <a href="{{ route('register') }}">Create one free</a>
        </p>

    </div>
</main>
@endsection
