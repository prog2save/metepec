<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email'    => 'required|email|unique:usuarios,email',
            'curp'     => 'nullable|string|max:18',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email'    => $request->email,
            'curp'     => $request->curp,
            'password' => Hash::make($request->password),
            'role'     => 'user', // rol por defecto al registrarse
        ]);

        Auth::login($usuario);

        return redirect('/dashboard');
    }
}