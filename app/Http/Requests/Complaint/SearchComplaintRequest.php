<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class SearchComplaintRequest extends FormRequest
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
            'complaint_number' => ['required', 'numeric'],
            'email' => ['required', 'email'],
            'phone_number' => ['required', 'numeric', 'digits:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'complaint_number.required' => 'رقم الشكوى مطلوب.',
            'complaint_number.numeric' => 'يجب أن يكون رقم الشكوى رقماً.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني المدخل غير صحيح.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.numeric' => 'يجب أن يكون رقم الهاتف رقماً.',
            'phone_number.digits' => 'يجب أن يتكون رقم الهاتف من 10 أرقام.',
        ];
    }
}
