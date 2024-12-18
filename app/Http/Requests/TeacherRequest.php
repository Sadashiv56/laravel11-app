<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'mobile' => 'required',
            'about_teacher' => 'required',
            'social_media' => 'required',
            'products' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name required.',
            'email.required' => 'Email required.',
            'mobile.required' => 'Mobile required.',
            'about_teacher.required' => 'About teacher required.',
            'social_media.required' => 'Select social media.',
            'products.required' => 'Select products.',
           
        ];
    }
}
