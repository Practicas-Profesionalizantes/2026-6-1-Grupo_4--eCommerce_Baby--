<?php

namespace App\Http\Controllers;

use App\Models\ClienteModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:usuario,Email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'Email' => $request->email,
            'Password_Hash' => Hash::make($request->password),
            'Id_rol' => 1,
        ]);

        ClienteModel::create([
            'Id_usuario' => $user->Id_usuario,
            'Nombre' => $request->name,
            'Apellido' => '',
            'DNI_CUIT' => null,
            'Telefono' => '00000000',
            'Condicion_IVA' => 'Consumidor Final',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('cuenta.index')->with('success', '¡Cuenta creada con éxito!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $clave = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($clave, 5)) {
            $segundos = RateLimiter::availableIn($clave);

            return back()->withErrors([
                'email' => 'Hiciste demasiados intentos. Probá de nuevo en ' . $segundos . ' segundos.',
            ])->onlyInput('email');
        }

        $authData = [
            'Email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($authData)) {
            RateLimiter::clear($clave);
            $request->session()->regenerate();

            return redirect()->route('cuenta.index');
        }

        RateLimiter::hit($clave, 60);

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}