<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;

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

            // Obtiene el usuario autenticado
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // Detecta información del dispositivo y navegador
            $agent = new Agent();
            $sistema = $agent->platform(); // Ejemplo: Android, Windows, iOS
            $app = $agent->browser(); // Ejemplo: Chrome, Safari, Firefox
            $version = $agent->version($app); // Ejemplo: 128.0.1

            // Guarda la hora y detalles de la última conexión
            if ($user) {
                $user->ultima_conexion = Carbon::now('America/Mexico_City');
                $user->sistema = $sistema ?? 'Desconocido';
                $user->app = ($app ? $app . ' (' . $version . ')' : 'Desconocido');
                $user->save();
            }

            return redirect()->route('dashboard');
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

