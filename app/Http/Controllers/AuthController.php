<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        if ($request->wantsJson()) return response()->json(['user' => $user], 201);
        return redirect('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }

        $user = Auth::user();
        if ($request->wantsJson()) return response()->json(['user' => $user]);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        if ($request->wantsJson()) return response()->json(['message' => 'Logged out']);
        return redirect('/login');
    }
}
