<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'id_agente_asignado' => ['nullable', 'exists:usuarios,id'],
                'id_servicio' => [
                    'required',
                    'integer',
                    Rule::exists('servicios', 'id')->where(
                        fn($q) =>
                        $q->where('id_direccion_municipal', $this->input('id_direccion_municipal'))
                    ),
                ],

                'asunto' => ['required', 'string', 'max:200'],
                'descripcion' => ['required', 'string'],

                'canal_ingreso' => ['nullable', 'string', 'max:50'],
                'prioridad' => ['required', 'in:Baja,Media,Alta,Urgente'],
                'tipo_ticket' => ['required', 'in:Pregunta,Incidente,Problema,Tarea'],

                'estado' => ['required', 'string', 'max:50'],
                'fecha_resolucion' => ['nullable', 'date', 'after_or_equal:today'],

                'latitud' => ['nullable', 'numeric'],
                'longitud' => ['nullable', 'numeric'],

                'observaciones' => ['nullable', 'string'],
            ];
        }
        return [
            'id_ciudadano' => ['required', 'exists:ciudadanos,id'],
            'id_direccion_municipal' => ['required', 'exists:direccion_municipals,id'],
            'id_agente_asignado' => ['nullable', 'exists:usuarios,id'],
            'id_servicio' => [
                'required',
                'integer',
                Rule::exists('servicios', 'id')->where(
                    fn($q) =>
                    $q->where('id_direccion_municipal', $this->input('id_direccion_municipal'))
                ),
            ],

            'asunto' => ['required', 'string', 'max:200'],
            'descripcion' => ['required', 'string'],

            'canal_ingreso' => ['nullable', 'string', 'max:50'],
            'prioridad' => ['required', 'in:Baja,Media,Alta,Urgente'],
            'tipo_ticket' => ['required', 'in:Pregunta,Incidente,Problema,Tarea'],

            'estado' => ['required', 'string', 'max:50'],

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
            'id_servicio.required' => 'El servicio es obligatorio.',
            'id_servicio.exists' => 'El servicio seleccionado no existe.',
            'id_agente_asignado.exists' => 'El agente asignado seleccionado no existe.',
            'asunto.required' => 'El asunto es obligatorio.',
            'asunto.string' => 'El asunto debe ser una cadena de texto.',
            'asunto.max' => 'El asunto no debe exceder los 200 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
