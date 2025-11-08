<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateJobRequest extends FormRequest
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
            // Basic job information
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:500',
            
            // Company information (optional - for new companies)
            'company' => 'sometimes|array',
            'company.name' => 'required_with:company|string|max:255',
            'company.description' => 'nullable|string',
            'company.website' => 'nullable|url',
            'company.email' => 'nullable|email',
            'company.phone' => 'nullable|string|max:20',
            'company.address' => 'nullable|string',
            'company.employee_count' => 'nullable|integer|min:1',
            'company.founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'company.industry' => 'nullable|string|max:255',
            
            // Job details
            'job_type' => 'required|string|in:full-time,part-time,contract,freelance,internship,remote,hybrid',
            'experience_level' => 'required|string|in:fresher,junior,middle,senior,lead,director',
            'salary_range' => 'required|string',
            'job_location' => 'required|string',
            'company_size' => 'required|string',
            
            // Requirements
            'required_skills' => 'required|array|min:1',
            'required_skills.*' => 'string',
            'education_level' => 'nullable|string',
            'english_level' => 'nullable|string',
            
            // Benefits & Contact
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'string',
            'application_deadline' => 'nullable|date|after:today',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'company_website' => 'nullable|url',
            'application_method' => 'required|string|in:email,online,direct,website',
            
            // Boolean flags
            'is_urgent' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            
            // Categories
            'categories' => 'required|array|min:1',
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
            'title.required' => 'Tiêu đề công việc là bắt buộc',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'company_name.required' => 'Tên công ty là bắt buộc',
            'description.required' => 'Mô tả công việc là bắt buộc',
            'short_description.required' => 'Mô tả ngắn là bắt buộc',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự',
            'job_type.required' => 'Loại hình công việc là bắt buộc',
            'experience_level.required' => 'Cấp độ kinh nghiệm là bắt buộc',
            'salary_range.required' => 'Mức lương là bắt buộc',
            'job_location.required' => 'Địa điểm làm việc là bắt buộc',
            'required_skills.required' => 'Kỹ năng yêu cầu là bắt buộc',
            'required_skills.min' => 'Phải có ít nhất 1 kỹ năng yêu cầu',
            'contact_email.required' => 'Email liên hệ là bắt buộc',
            'contact_email.email' => 'Email liên hệ không hợp lệ',
            'application_deadline.after' => 'Hạn ứng tuyển phải sau ngày hôm nay',
            'categories.required' => 'Danh mục công việc là bắt buộc',
            'categories.min' => 'Phải chọn ít nhất 1 danh mục',
            'categories.*.exists' => 'Danh mục được chọn không tồn tại',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Custom validation logic if needed
            $data = $this->all();
            
            // Validate salary range format
            if (isset($data['salary_range'])) {
                $validSalaryRanges = [
                    'under-10m', '10m-20m', '20m-30m', '30m-50m', 
                    '50m-80m', 'over-80m', 'negotiable'
                ];
                if (!in_array($data['salary_range'], $validSalaryRanges)) {
                    $validator->errors()->add('salary_range', 'Mức lương không hợp lệ');
                }
            }
            
            // Validate experience level
            if (isset($data['experience_level'])) {
                $validLevels = ['fresher', 'junior', 'middle', 'senior', 'lead', 'director'];
                if (!in_array($data['experience_level'], $validLevels)) {
                    $validator->errors()->add('experience_level', 'Cấp độ kinh nghiệm không hợp lệ');
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