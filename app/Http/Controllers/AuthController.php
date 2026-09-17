<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $throttleKey = mb_strtolower($request->string('username')->toString()).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'username' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.',
            ]);
        }

        $credentials = [
            'username' => $request->string('username')->toString(),
            'password' => $request->string('password')->toString(),
            'status' => true,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'username' => 'Username atau password tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'login',
            'description' => 'User berhasil login.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang di SIPUS.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user() !== null) {
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => 'logout',
                'description' => 'User logout.',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
