<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic job information
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'short_description' => 'nullable|string|max:500',
            
            // Job details
            'job_type' => 'required|string|in:full-time,part-time,contract,freelance,internship',
            'experience_level' => 'required|string|in:entry,junior,mid,senior,lead,executive',
            'salary_range' => 'nullable|string',
            'job_location' => 'required|string|max:255',
            
            // Company information
            'company_name' => 'required|string|max:255',
            'company_size' => 'nullable|string|in:1-10,11-50,51-200,201-500,500+',
            'company_website' => 'nullable|url|max:255',
            
            // Skills and requirements
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'string|max:100',
            'education_level' => 'nullable|string|in:high-school,bachelor,master,phd,none',
            'english_level' => 'nullable|string|in:basic,intermediate,advanced,native',
            
            // Benefits and application
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'string|max:100',
            'application_deadline' => 'nullable|date|after:today',
            
            // Contact information
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            
            // Job flags
            'is_urgent' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            
            // Categories (subcategories of job category)
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            
            // SEO fields (optional)
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề công việc là bắt buộc.',
            'title.max' => 'Tiêu đề công việc không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả công việc là bắt buộc.',
            'description.min' => 'Mô tả công việc phải có ít nhất 100 ký tự.',
            'job_type.required' => 'Loại hình công việc là bắt buộc.',
            'job_type.in' => 'Loại hình công việc không hợp lệ.',
            'experience_level.required' => 'Mức kinh nghiệm là bắt buộc.',
            'experience_level.in' => 'Mức kinh nghiệm không hợp lệ.',
            'job_location.required' => 'Địa điểm làm việc là bắt buộc.',
            'company_name.required' => 'Tên công ty là bắt buộc.',
            'contact_email.required' => 'Email liên hệ là bắt buộc.',
            'contact_email.email' => 'Email liên hệ không hợp lệ.',
            'company_website.url' => 'Website công ty không hợp lệ.',
            'application_deadline.after' => 'Hạn ứng tuyển phải sau ngày hôm nay.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề công việc',
            'description' => 'mô tả công việc',
            'short_description' => 'mô tả ngắn',
            'job_type' => 'loại hình công việc',
            'experience_level' => 'mức kinh nghiệm',
            'salary_range' => 'mức lương',
            'job_location' => 'địa điểm làm việc',
            'company_name' => 'tên công ty',
            'company_size' => 'quy mô công ty',
            'company_website' => 'website công ty',
            'required_skills' => 'kỹ năng yêu cầu',
            'education_level' => 'trình độ học vấn',
            'english_level' => 'trình độ tiếng Anh',
            'job_benefits' => 'phúc lợi',
            'application_deadline' => 'hạn ứng tuyển',
            'contact_email' => 'email liên hệ',
            'contact_phone' => 'số điện thoại liên hệ',
        ];
    }
}
