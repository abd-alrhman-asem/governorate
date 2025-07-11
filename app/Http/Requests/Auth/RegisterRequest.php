<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب.',
            'first_name.string' => 'يجب أن يكون الاسم الأول نصًا.',
            'first_name.max' => 'يجب ألا يتجاوز الاسم الأول :max حرفًا.',

            'last_name.required' => 'الاسم الأخير مطلوب.',
            'last_name.string' => 'يجب أن يكون الاسم الأخير نصًا.',
            'last_name.max' => 'يجب ألا يتجاوز الاسم الأخير :max حرفًا.',

            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone_number.in' => 'يجب أن يكون رقم الهاتف مكونًا من 10 أرقام.', // ملاحظة: قاعدة 'in:10' تتحقق من أن القيمة هي "10" بالضبط وليس الطول.
            // إذا كنت تقصد الطول، استخدم 'digits:10' أو 'min:10|max:10'.

            'email.required' => 'عنوان البريد الإلكتروني مطلوب.',
            'email.string' => 'يجب أن يكون عنوان البريد الإلكتروني نصًا.',
            'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صالح.',
            'email.max' => 'يجب ألا يتجاوز عنوان البريد الإلكتروني :max حرفًا.',
            'email.unique' => 'عنوان البريد الإلكتروني هذا مسجل بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن :min أحرف.',
            'password.mixed_case' => 'يجب أن تحتوي كلمة المرور على أحرف كبيرة وصغيرة.',
            'password.numbers' => 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل.',
        ];
    }

}
