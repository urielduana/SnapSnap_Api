<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'phone' => ['numeric', 'min_digits:10', 'unique:users'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'bio' => ['string'],
            'password' => ['required', 'string'],            
        ];
    }
}
