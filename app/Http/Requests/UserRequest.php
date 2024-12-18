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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'required|digits_between:10,15',
            'status' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name required',
            'last_name.required' => 'Last name required',
            'email.required' => 'Email required',
            'password.required' => 'Password required',
            'mobile.required' => 'Phone number required',
            'status.required' => 'Status required',

        ];
    }
}
