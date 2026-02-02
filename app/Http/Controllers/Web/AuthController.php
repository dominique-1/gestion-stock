<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Déconnecter si déjà connecté pour permettre de se reconnecter
        if (session()->has('user')) {
            session()->forget('user');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Valider les entrées
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Créer utilisateur simple en session (accepte n'importe quel email/password)
        $user = [
            'id' => 1,
            'name' => 'Admin',
            'email' => $request->email,
            'role' => 'admin',
            'is_active' => true,
        ];

        session(['user' => $user]);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
