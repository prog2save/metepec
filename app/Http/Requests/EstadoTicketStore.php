<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstadoTicketStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $estadoId = $this->route('estado');
            return [
                'categoria' => ['required', 'in:Abierto,Pendiente,Resuelto'],
                'nombre_agente' => ['required', 'string', 'max:50'],
                'descripcion_agente' => ['required', 'string', 'max:200'],
                'nombre_usuario' => ['nullable', 'string', 'max:50'],
                'descripcion_usuario' => ['nullable', 'string', 'max:200'],
                'vista_usuario' => ['nullable', 'boolean'],
            ];
        }
        return [
            'categoria' => ['required', 'in:Nuevo,Abierto,Pendiente,Resuelto'],
            'nombre_agente' => ['required', 'string', 'max:50'],
            'descripcion_agente' => ['required', 'string', 'max:200'],
            'nombre_usuario' => ['nullable', 'string', 'max:50'],
            'descripcion_usuario' => ['nullable', 'string', 'max:200']
        ];
    }

    public function messages(): array
    {
        return [
            'categoria.required' => 'La categoría es obligatoria',
            'nombre_agente.required' => 'El nombre del estado es obligatorio',
            'nombre_agente.max' => 'El nombre del estado no debe de exceder los 50 caracteres',
            'descripcion_agente.required' => 'La descripción es obligatoria',
            'descripcion_agente.max' => 'La descripción no debe de exceder los 200 caracteres',
            'nombre_usuario.max' => 'El nombre del estado para la vista del usuario final no debe exceder los 50 caracteres',
            'descripcion_usuario.max' => 'La descripción del estado para la vista del usuario final no debe exceder los 200 caracteres'
        ];
    }
}
