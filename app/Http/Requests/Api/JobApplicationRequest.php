<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'applicant_name'  => 'required|string|min:2|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'nullable|string|max:20',
            'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter'    => 'nullable|string|max:5000',
            'additional_info' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_name.required'  => 'Vui lòng nhập họ và tên.',
            'applicant_email.required' => 'Vui lòng nhập email.',
            'applicant_email.email'    => 'Email không đúng định dạng.',
            'resume.mimes'             => 'Chỉ chấp nhận file PDF, DOC hoặc DOCX.',
            'resume.max'               => 'Kích thước file không được vượt quá 5MB.',
        ];
    }
}
