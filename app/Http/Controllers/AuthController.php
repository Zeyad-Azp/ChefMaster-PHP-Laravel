<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Show register form ────────────────────────────────────
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // ── Handle register form ──────────────────────────────────
    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname'              => 'required|string|max:100',
            'email'                 => 'required|email|max:255|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'avatar'                => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ], [
            'fullname.required'             => 'Full name is required.',
            'fullname.max'                  => 'Full name cannot exceed 100 characters.',
            'email.required'                => 'Email address is required.',
            'email.email'                   => 'Please enter a valid email address.',
            'email.unique'                  => 'An account with this email already exists.',
            'password.required'             => 'Password is required.',
            'password.min'                  => 'Password must be at least 8 characters.',
            'password.confirmed'            => 'Passwords do not match.',
            'avatar.image'                  => 'Avatar must be a valid image.',
            'avatar.mimes'                  => 'Avatar must be a JPG, PNG, or WebP file.',
            'avatar.max'                    => 'Avatar must be under 2 MB.',
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = '/storage/' . $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'fullname'    => $validated['fullname'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'avatar_path' => $avatarPath,
        ]);

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Welcome to ChefMaster, ' . ($user->fullname ?: 'chef') . '!');
    }

    // ── Show login form ───────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    // ── Handle login form ─────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email address is required.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back, ' . (Auth::user()->fullname ?: 'chef') . '!');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    // ── Logout ────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }
}
