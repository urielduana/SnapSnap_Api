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
        return false;
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
            'phone' => ['numeric', 'min_digits:10'],
            'username' => ['required', 'string'],
            'email' => ['required', 'email'],
            'bio' => ['string'],
            'password' => ['required', 'string'],            
        ];
    }
}
