<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home'); // Redirige vers la page d'accueil après connexion
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login'); // Redirige vers la page de connexion après déconnexion
    }
}
