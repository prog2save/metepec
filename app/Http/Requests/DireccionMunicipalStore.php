<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DireccionMunicipalStore extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array 
    {
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $direccionId = $this->route('direccion_municipal');

            return[
                'nombre_direccion' => ['required', 'string', 'max:255'],
                'contacto_principal' => ['nullable', 'string', 'max:255'],
                'telefono' => ['nullable', 'string', 'max:10'],
                'email' => ['nullable', 'string', 'max:255'],
            ];
        }
        return[
                'nombre_direccion' => ['required', 'string', 'max:255'],
                'contacto_principal' => ['nullable', 'string', 'max:255'],
                'telefono' => ['nullable', 'string', 'max:10'],
                'email' => ['nullable', 'string', 'max:255'],
            ];
    }

    public function messages(): array 
    {
        return [
            'nombre_direccion.required' => 'El nombre es obligatorio.',
            'nombre_direccion.string' => 'El nombre debe ser una cadena de texto.',
            'nombre_direccion.max' => 'El nombre no debe exceder los 255 caracteres.',
        ];
    }
}