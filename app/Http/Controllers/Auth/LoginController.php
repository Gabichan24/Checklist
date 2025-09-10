<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Muestra el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesa el login
    public function login(Request $request)
    {
        // Valida los datos
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        // Credenciales para autenticación
        $credentials = $request->only('correo', 'password');

        // Intenta autenticar
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Si falla, regresa con error
        return back()->withErrors([
            'correo' => 'Las credenciales no son válidas.',
        ])->onlyInput('correo');
    }

    // Cierra sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

