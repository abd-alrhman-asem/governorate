<?php

namespace App\Http\Requests\Complaint;

use App\Domain\Complaint\DataTransferObjects\ComplaintData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreComplaintRequest extends FormRequest
{

    /**
     * تحديد ما إذا كان المستخدم مصرحًا بتقديم هذا الطلب.
     */
    public function authorize(): bool
    {

        return true; // يجب تعديل هذا بناءً على صلاحيات المستخدم
    }

    public function rules(): array
    {
        return [
            'destination_id' => ['required', 'string', 'max:255', 'exists:destinations,id'],
            'category_id' => ['required', 'string', 'max:255', 'exists:complaint_categories,id'],
            'type_id' => ['required', 'string', 'max:255', 'exists:complaint_types,id'],

            'title' => ['required', 'string', 'min:3', 'max:255'],

            'text' => ['required', 'string', 'min:10', 'max:5000'],
            'LocationText' => ['nullable', 'string', 'max:500'],
            'LocationLat' => ['nullable', 'numeric', 'between:-90,90'],
            'LocationLng' => ['nullable', 'numeric', 'between:-180,180'],

            'attachments' => ['sometimes', 'array', 'max:5'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,webp', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'destination_id.required' => 'معرف الوجهة مطلوب.',
            'destination_id.string' => 'معرف الوجهة يجب أن يكون نصًا.',
            'destination_id.max' => 'معرف الوجهة لا يمكن أن يتجاوز :max حرفًا.',
            'destination_id.exists' => 'الوجهة المحددة غير موجودة.',

            'category_id.required' => 'معرف الفئة مطلوب.',
            'category_id.string' => 'معرف الفئة يجب أن يكون نصًا.',
            'category_id.max' => 'معرف الفئة لا يمكن أن يتجاوز :max حرفًا.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',

            'type_id.required' => 'معرف النوع مطلوب.',
            'type_id.string' => 'معرف النوع يجب أن يكون نصًا.',
            'type_id.max' => 'معرف النوع لا يمكن أن يتجاوز :max حرفًا.',
            'type_id.exists' => 'النوع المحدد غير موجود.',

            'title.required' => 'العنوان مطلوب.',
            'title.string' => 'العنوان يجب أن يكون نصًا.',
            'title.min' => 'العنوان يجب أن لا يقل عن :min أحرف.',
            'title.max' => 'العنوان لا يمكن أن يتجاوز :max أحرف.',
            'title.unique' => 'هذا العنوان موجود بالفعل. الرجاء اختيار عنوان آخر.',

            'text.required' => 'المحتوى النصي مطلوب.',
            'text.string' => 'المحتوى النصي يجب أن يكون نصًا.',
            'text.min' => 'المحتوى النصي يجب أن لا يقل عن :min أحرف.',
            'text.max' => 'المحتوى النصي لا يمكن أن يتجاوز :max أحرف.',

            'LocationText.string' => 'نص الموقع يجب أن يكون نصًا.',
            'LocationText.max' => 'نص الموقع لا يمكن أن يتجاوز :max حرفًا.',

            'LocationLat.numeric' => 'خط العرض يجب أن يكون رقمًا.',
            'LocationLat.between' => 'خط العرض يجب أن يكون بين :min و :max.',

            'LocationLng.numeric' => 'خط الطول يجب أن يكون رقمًا.',
            'LocationLng.between' => 'خط الطول يجب أن يكون بين :min و :max.',

            'attachments.array' => 'المرفقات يجب أن تكون في صيغة مصفوفة.',
            'attachments.max' => 'عدد المرفقات لا يمكن أن يتجاوز :max ملفات.',

            'attachments.*.file' => 'كل مرفق يجب أن يكون ملفًا.',
            'attachments.*.mimes' => 'صيغة الملف للمرفق غير مدعومة. الصيغ المدعومة هي: :values.',
            'attachments.*.max' => 'حجم الملف للمرفق لا يمكن أن يتجاوز :max كيلوبايت.',
        ];
    }

    public function toDto(): ComplaintData
    {
        $validated = $this->validated();

        $attachments = [];
        if ($this->hasFile('attachments')) {
            $attachments = $this->file('attachments');
            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }
            $attachments = array_filter($attachments, fn($file) => $file instanceof UploadedFile);
        }
        return new ComplaintData(
            // need to change this
            userId: auth()->user()->id ?? 2,
            destinationId: (int)$validated['destination_id'],
            complaintCategoryId: (int)$validated['category_id'],
            complaintTypeId: isset($validated['type_id']) ? (int)$validated['type_id'] : null,
            text: $validated['text'],
            title: $validated['title'],
            locationText: $validated['LocationText'],
            locationLat: (string)($validated['LocationLat'] ?? ''),
            locationLng: (string)($validated['LocationLng'] ?? ''),
            attachments: $attachments,
        );
    }
}
