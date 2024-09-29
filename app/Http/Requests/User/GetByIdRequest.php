<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class GetByIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'userId.required' => 'The user ID is required.',
            'userId.integer' => 'The user must be an integer.',
            'userId.min' => 'The user ID must be at least 1.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'The user with the requestedid does not exist',
            'fails' => $validator->errors(),
        ], 400));
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'userId' => $this->route('userId'),
        ]);
    }
}
