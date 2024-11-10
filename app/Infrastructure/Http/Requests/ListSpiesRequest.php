<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListSpiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'age' => 'sometimes|integer|min:0|prohibits:age_range', // Age cannot exist with age_range
            'age_range' => [
                'sometimes',
                'regex:/^\d+-\d+$/', // Requires both min and max ages in the format "min-max"
                'prohibits:age',
                function ($attribute, $value, $fail) {
                    $range = explode('-', $value);
                    if (sizeof($range) !== 2) {
                        return;
                    }
                    if ($range[0] >= $range[1]) {
                        $fail('The from age must be less than the to age in age_range.');
                    }
                },
            ], 'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'sort' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'age_range.regex' => 'The age_range format is invalid. It should be in the format "min-max" with both ages provided.',
            'sort.string' => 'The sort parameter must be a valid string.',
            'age.prohibited_with' => 'The age field cannot be used with age_range.',
            'age_range.prohibited_with' => 'The age_range field cannot be used with age.',
        ];
    }
}
