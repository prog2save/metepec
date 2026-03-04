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
        $regexCurp = '/^[A-ZÑ]{4}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z][0-9]$/';

        // Obtener id para ignore unique en update
        $usuarioParam = $this->route('usuario');
        $usuarioId = $usuarioParam instanceof \App\Models\Usuario ? $usuarioParam->id : $usuarioParam;

        $base = [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:10'],
            'role' => ['required', 'in:admin,ciudadano,agente'], // o 'rol'
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $base + [
                'email' => ['required', 'email', "unique:usuarios,email,{$usuarioId},id"],
                'curp' => ['required', 'string', 'size:18', "unique:usuarios,curp,{$usuarioId},id", 'regex:' . $regexCurp],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ];
        }

        return $base + [
            'email' => ['required', 'email', 'unique:usuarios,email'],
            'curp' => ['required', 'string', 'size:18', 'unique:usuarios,curp', 'regex:' . $regexCurp],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->curp) {
            $this->merge(['curp' => strtoupper($this->curp)]);
        }
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
