<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $ticketId = $this->route('ticket');
            return [
                'id_ciudadano' => ['required', 'exists:ciudadanos,id'],
                'id_direccion_municipal' => ['required', 'exists:direccion_municipals,id'],
                'id_agente_asignado' => ['required', 'exists:usuarios,id'],

                'asunto' => ['required', 'string', 'max:200'],
                'descripcion' => ['required', 'string'],

                'tipo_servicio' => ['required', 'string', 'max:100'],
                'canal_ingreso' => ['nullable', 'string', 'max:50'],
                'prioridad' => ['required', 'in:Baja,Media,Alta,Urgente'],
                'tipo_ticket' => ['required', 'in:Pregunta,Incidente,Problema,Tarea'],

                'estado' => ['required', 'in:Nuevo,Abierto,Pendiente,Resuelto'],
                'fecha_resolucion' => ['nullable', 'date', 'after_or_equal:today'],

                'latitud' => ['nullable', 'numeric'],
                'longitud' => ['nullable', 'numeric'],

                'observaciones' => ['nullable', 'string'],
            ];
        }
        return [
            'id_ciudadano' => ['required', 'exists:ciudadanos,id'],
            'id_direccion_municipal' => ['required', 'exists:direccion_municipals,id'],
            'id_agente_asignado' => ['required', 'exists:usuarios,id'],

            'asunto' => ['required', 'string', 'max:200'],
            'descripcion' => ['required', 'string'],

            'tipo_servicio' => ['required', 'string', 'max:100'],
            'canal_ingreso' => ['nullable', 'string', 'max:50'],
            'prioridad' => ['required', 'in:Baja,Media,Alta,Urgente'],
            'tipo_ticket' => ['required', 'in:Pregunta,Incidente,Problema,Tarea'],

            'estado' => ['required', 'in:Nuevo,Abierto,Pendiente,Resuelto'],

            'direccion_texto' => ['nullable', 'string', 'max:255'],
            'latitud' => ['nullable', 'numeric'],
            'longitud' => ['nullable', 'numeric'],

            'observaciones' => ['nullable', 'string'],
            'adjuntos' => ['nullable', 'array'],
            'adjuntos.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:5120', // 5 MB por archivo
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_ciudadano.required' => 'El ciudadano es obligatorio.',
            'id_ciudadano.exists' => 'El ciudadano seleccionado no existe.',
            'id_direccion_municipal.required' => 'La dirección municipal es obligatoria.',
            'id_direccion_municipal.exists' => 'La dirección municipal seleccionada no existe.',
            'id_agente_asignado.required' => 'El agente asignado es obligatorio.',
            'id_agente_asignado.exists' => 'El agente asignado seleccionado no existe.',
            'asunto.required' => 'El asunto es obligatorio.',
            'asunto.string' => 'El asunto debe ser una cadena de texto.',
            'asunto.max' => 'El asunto no debe exceder los 200 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
