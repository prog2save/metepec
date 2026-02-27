<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CiudadanoStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('patch') || $this->isMethod('put')) {

            $ciudadanoId = $this->route('ciudadano');

            return [
                'nombre' => ['required', 'string', 'max:255'],
                'apellido_paterno' => ['required', 'string', 'max:255'],
                'apellido_materno' => ['required', 'string', 'max:255'],

                'telefono_principal' => ['required', 'string', 'max:10'],
                'telefono_alterno' => ['nullable', 'string', 'max:10'],

                'email' => ['nullable', 'email', 'max:255'],

                'direccion_calle' => ['required', 'string', 'max:255'],
                'direccion_numero' => ['nullable', 'string', 'max:50'],
                'direccion_colonia' => ['required', 'string', 'max:255'],

                'latitud' => ['nullable', 'numeric'],
                'longitud' => ['nullable', 'numeric'],

                'historial_interacciones' => ['nullable'],
            ];
        }
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],

            'telefono_principal' => ['required', 'string', 'max:30'],
            'telefono_alterno' => ['nullable', 'string', 'max:30'],

            'email' => ['nullable', 'email', 'max:255'],

            'direccion_calle' => ['required', 'string', 'max:255'],
            'direccion_numero' => ['nullable', 'numeric'],
            'direccion_colonia' => ['required', 'string', 'max:255'],

            'latitud' => ['nullable', 'numeric'],
            'longitud' => ['nullable', 'numeric'],

            'historial_interacciones' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'telefono_principal.required' => 'El teléfono principal es obligatorio.',
            'direccion_calle.required' => 'La calle es obligatoria.',
            'direccion_colonia.required' => 'La colonia es obligatoria.',
        ];
    }
}
