<?php

namespace App\Infrastructure\Http\Requests;

use App\Domain\ValueObjects\Agency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // You can add authorization logic here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required', 'string', 'max:255',
                Rule::unique('spies')->where(function ($query) {
                    return $query->where('last_name', $this->input('last_name'));
                }),
            ],
            'last_name' => 'required|string|max:255',
            'agency' => ['required', 'string', 'in:' . implode(',', Agency::values())],
            'country_of_operation' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'date_of_death' => 'nullable|date|after:date_of_birth',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.unique' => 'A spy with the same first and last name already exists.',
        ];
    }
}
