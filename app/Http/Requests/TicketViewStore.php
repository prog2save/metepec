<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TicketViewStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'visibility'                 => ['required', 'in:all_agents,only_me'],
            'active'                     => ['boolean'],

            'conditions'                 => ['nullable', 'array'],
            'conditions.*.match_type'    => ['required_with:conditions', 'in:all,any'],
            'conditions.*.field'         => ['nullable', 'string', 'max:100'],
            'conditions.*.operator'      => ['nullable', 'string', 'max:50'],
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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $conditions = $this->input('conditions', []);

            $operatorsThatNeedValue = [
                'is',
                'is_not',
                'contains',
                'not_contains',
                'less_than',
                'greater_than',
                'is_before',
                'is_after',
            ];

            $validConditionsCount = 0;

            foreach ($conditions as $index => $condition) {
                $field = $condition['field'] ?? null;
                $operator = $condition['operator'] ?? null;
                $value = $condition['value'] ?? null;

                $hasAnyData = filled($field) || filled($operator) || filled($value);

                // Ignora filas completamente vacías
                if (! $hasAnyData) {
                    continue;
                }

                if (blank($field)) {
                    $validator->errors()->add(
                        "conditions.$index.field",
                        "Debes seleccionar un campo en la condición."
                    );
                }

                if (blank($operator)) {
                    $validator->errors()->add(
                        "conditions.$index.operator",
                        "Debes seleccionar un operador en la condición."
                    );
                }

                if (in_array($operator, $operatorsThatNeedValue, true) && blank($value)) {
                    $validator->errors()->add(
                        "conditions.$index.value",
                        "Debes seleccionar o escribir un valor en la condición."
                    );
                }

                $isComplete =
                    filled($field) &&
                    filled($operator) &&
                    (! in_array($operator, $operatorsThatNeedValue, true) || filled($value));

                if ($isComplete) {
                    $validConditionsCount++;
                }
            }

            if ($validConditionsCount === 0) {
                $validator->errors()->add(
                    'conditions',
                    'Debes agregar al menos una condición válida.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required'                      => 'El nombre de la vista es obligatorio.',
            'title.max'                           => 'El nombre no puede superar 255 caracteres.',
            'visibility.required'                 => 'La visibilidad es obligatoria.',
            'visibility.in'                       => 'La visibilidad debe ser all_agents o only_me.',
            'conditions.*.match_type.in'          => 'El tipo de condición debe ser all o any.',
            'columns.*.column_key.required_with'  => 'La clave de columna es obligatoria.',
            'sorts.*.sort_type.in'                => 'El tipo de orden debe ser group_by o order_by.',
            'sorts.*.direction.in'                => 'La dirección debe ser asc o desc.',
        ];
    }
}