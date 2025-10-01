<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Có thể thêm logic authorization sau
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic job information - không required cho update
            'title' => 'sometimes|string|max:255',
            'company_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'short_description' => 'sometimes|string|max:500',
            
            // Job details
            'job_type' => 'sometimes|string|in:full-time,part-time,contract,freelance,internship,remote,hybrid',
            'experience_level' => 'sometimes|string|in:fresher,junior,middle,senior,lead,director',
            'salary_range' => 'sometimes|string',
            'job_location' => 'sometimes|string',
            'company_size' => 'sometimes|string',
            
            // Requirements
            'required_skills' => 'sometimes|array|min:1',
            'required_skills.*' => 'string',
            'education_level' => 'nullable|string',
            'english_level' => 'nullable|string',
            
            // Benefits & Contact
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'string',
            'application_deadline' => 'nullable|date|after:today',
            'contact_email' => 'sometimes|email',
            'contact_phone' => 'nullable|string|max:20',
            'company_website' => 'nullable|url',
            'application_method' => 'sometimes|string|in:email,online,direct,website',
            
            // Boolean flags
            'is_urgent' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'status' => 'sometimes|boolean',
            
            // Categories
            'categories' => 'sometimes|array|min:1',
            'categories.*' => 'integer|exists:categories,id',
            
            // SEO fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự',
            'required_skills.min' => 'Phải có ít nhất 1 kỹ năng yêu cầu',
            'contact_email.email' => 'Email liên hệ không hợp lệ',
            'application_deadline.after' => 'Hạn ứng tuyển phải sau ngày hôm nay',
            'categories.min' => 'Phải chọn ít nhất 1 danh mục',
            'categories.*.exists' => 'Danh mục được chọn không tồn tại',
            'company_website.url' => 'Website công ty không hợp lệ',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $data = $this->all();
            
            // Validate salary range format if present
            if (isset($data['salary_range'])) {
                $validSalaryRanges = [
                    'under-10m', '10m-20m', '20m-30m', '30m-50m', 
                    '50m-80m', 'over-80m', 'negotiable'
                ];
                if (!in_array($data['salary_range'], $validSalaryRanges)) {
                    $validator->errors()->add('salary_range', 'Mức lương không hợp lệ');
                }
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}