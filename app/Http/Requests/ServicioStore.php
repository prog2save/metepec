<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicioStore extends FormRequest{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array 
    {
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $servicioId = $this->route('servicio');
            return [
                'id_direccion_municipal' => ['required', 'exists:direccion_municipals,id'],
                'nombre_servicio' => ['required', 'string', 'max:100'],
            ];
        }
        return [
            'id_direccion_municipal' => ['required', 'exists:direccion_municipals,id'],
            'nombre_servicio' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_direccion_municipal.required' => 'La dirección municipal es obligatoria.',
            'id_direccion_municipal.exists' => 'La dirección municipal seleccionada no existe.',
            'nombre_servicio.required' => 'El nombre del servicio es obligatorio.',
            'nombre_servicio.string' => 'El nombre del servicio debe ser una cadena de texto.',
        ];
    }
}