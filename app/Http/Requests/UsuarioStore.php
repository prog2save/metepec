<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('patch') || $this->isMethod('put')) {

            $usuarioId = $this->route('usuario');
            // si usas Route Model Binding esto ya es el modelo

            return [
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'telefono' => 'nullable|string|max:10',

                'email' => 'required|email|unique:usuarios,email,' . $usuarioId,
                'curp' => 'required|string|max:18|unique:usuarios,curp,' . $usuarioId,

                'password' => 'nullable|string|min:8|confirmed',
            ];
        }
        return [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:10',
            'email' => 'required|email|unique:usuarios,email',
            'curp' => 'required|string|max:18|unique:usuarios,curp',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'curp.required' => 'La CURP es obligatoria.',
            'password.required' => 'La contraseña es obligatoria.',
            'email.unique' => 'El correo ya está registrado.',
            'curp.unique' => 'La CURP ya está registrada.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
