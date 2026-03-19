<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketViewStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'title'                      => ['required', 'string', 'max:255'],
                'description'                => ['nullable', 'string'],
                'visibility'                 => ['required', 'in:all_agents,only_me'],
                'active'                     => ['boolean'],

                'conditions'                 => ['nullable', 'array'],
                'conditions.*.match_type'    => ['required_with:conditions', 'in:all,any'],
                'conditions.*.field'         => ['required_with:conditions', 'string', 'max:100'],
                'conditions.*.operator'      => ['required_with:conditions', 'string', 'max:50'],
                'conditions.*.value'         => ['nullable', 'string'],

                'columns'                    => ['nullable', 'array'],
                'columns.*.column_key'       => ['required_with:columns', 'string', 'max:100'],
                'columns.*.label'            => ['nullable', 'string', 'max:100'],
                'columns.*.position'         => ['nullable', 'integer', 'min:0'],

                'sorts'                      => ['nullable', 'array'],
                'sorts.*.sort_type'          => ['required_with:sorts', 'in:group_by,order_by'],
                'sorts.*.column_key'         => ['required_with:sorts', 'string', 'max:100'],
                'sorts.*.direction'          => ['nullable', 'in:asc,desc'],
            ];
        }

        return [
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'visibility'                 => ['required', 'in:all_agents,only_me'],
            'active'                     => ['boolean'],

            'conditions'                 => ['nullable', 'array'],
            'conditions.*.match_type'    => ['required_with:conditions', 'in:all,any'],
            'conditions.*.field'         => ['required_with:conditions', 'string', 'max:100'],
            'conditions.*.operator'      => ['required_with:conditions', 'string', 'max:50'],
            'conditions.*.value'         => ['nullable', 'string'],

            'columns'                    => ['nullable', 'array'],
            'columns.*.column_key'       => ['required_with:columns', 'string', 'max:100'],
            'columns.*.label'            => ['nullable', 'string', 'max:100'],
            'columns.*.position'         => ['nullable', 'integer', 'min:0'],

            'sorts'                      => ['nullable', 'array'],
            'sorts.*.sort_type'          => ['required_with:sorts', 'in:group_by,order_by'],
            'sorts.*.column_key'         => ['required_with:sorts', 'string', 'max:100'],
            'sorts.*.direction'          => ['nullable', 'in:asc,desc'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                      => 'El nombre de la vista es obligatorio.',
            'title.max'                           => 'El nombre no puede superar 255 caracteres.',
            'visibility.required'                 => 'La visibilidad es obligatoria.',
            'visibility.in'                       => 'La visibilidad debe ser all_agents o only_me.',
            'conditions.*.match_type.in'          => 'El tipo de condición debe ser all o any.',
            'conditions.*.field.required_with'    => 'El campo de la condición es obligatorio.',
            'conditions.*.operator.required_with' => 'El operador de la condición es obligatorio.',
            'columns.*.column_key.required_with'  => 'La clave de columna es obligatoria.',
            'sorts.*.sort_type.in'                => 'El tipo de orden debe ser group_by o order_by.',
            'sorts.*.direction.in'                => 'La dirección debe ser asc o desc.',
        ];
    }
}