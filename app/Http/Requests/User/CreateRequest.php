<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;

class CreateRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email:rfc,dns',
            'phone' => ['required', 'regex:/^\+380\d{9}$/'],
            'position_id' => 'required|exists:positions,id',
            'photo' => 'required|image|mimes:jpeg,jpg|dimensions:min_width=70,min_height=70|max:5120',
        ];
    }

    public function messages(): array 
    {
        return [
            'name.required' => 'User name is required.',
            'name.min' => 'User name must be at least 2 characters.',
            'name.max' => 'User name must not exceed 60 characters.',
            'email.required' => 'User email is required.',
            'email.email' => 'User email must be a valid email address.',
            'phone.required' => 'User phone number is required.',
            'phone.regex' => 'Phone number must start with +380 and contain 9 digits after the code.',
            'position_id.required' => 'Position ID is required.',
            'position_id.exists' => 'Selected position does not exist.',
            'photo.required' => 'User photo is required.',
            'photo.image' => 'User photo must be an image.',
            'photo.mimes' => 'User photo must be a jpg or jpeg file.',
            'photo.dimensions' => 'User photo must have at least 70x70px resolution.',
            'photo.max' => 'User photo must not exceed 5MB in size.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'fails' => $validator->errors(),
        ], 422));
    }

    // public function withValidator(Validator $validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->phoneOrEmailExists()) {
    //             $validator->errors()->add('exists', 'User with this phone or email already exists');
    //         }
    //     });
    // }

    public function phoneOrEmailExists()
    {
        return User::where('phone', $this->phone)->orWhere('email', $this->email)->exists();
    }
}
