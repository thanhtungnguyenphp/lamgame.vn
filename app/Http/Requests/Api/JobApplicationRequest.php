<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class JobApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow both authenticated and guest users
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\p{L}\s\-\.\']+$/u', // Unicode letters, spaces, hyphens, dots, apostrophes
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^(\+84|84|0)(3[2-9]|5[6|8|9]|7[0|6-9]|8[1-9]|9[0-9])[0-9]{7}$/', // Vietnam phone format
            ],
            'cv' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120', // 5MB in KB
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'cover_letter' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'experience' => [
                'nullable',
                'string',
                'in:fresher,junior,middle,senior',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ và tên',
            'full_name.min' => 'Họ và tên phải có ít nhất 2 ký tự',
            'full_name.max' => 'Họ và tên không được vượt quá 100 ký tự',
            'full_name.regex' => 'Họ và tên chỉ được chứa chữ cái, khoảng trắng, dấu gạch ngang và dấu chấm',
            
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng Việt Nam',
            
            'cv.required' => 'Vui lòng tải lên file CV',
            'cv.file' => 'CV phải là một file',
            'cv.mimes' => 'CV chỉ chấp nhận định dạng PDF, DOC hoặc DOCX',
            'cv.max' => 'Kích thước CV không được vượt quá 5MB',
            'cv.mimetypes' => 'Định dạng file CV không được hỗ trợ',
            
            'cover_letter.max' => 'Thư giới thiệu không được vượt quá 2000 ký tự',
            
            'experience.in' => 'Mức kinh nghiệm không hợp lệ',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'họ và tên',
            'email' => 'email',
            'phone' => 'số điện thoại',
            'cv' => 'CV',
            'cover_letter' => 'thư giới thiệu',
            'experience' => 'kinh nghiệm',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'error' => 'VALIDATION_ERROR',
                'errors' => $validator->errors(),
                'details' => $this->getValidationErrorDetails($validator)
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    /**
     * Get detailed validation error information
     */
    private function getValidationErrorDetails(Validator $validator): array
    {
        $details = [];
        
        foreach ($validator->errors()->messages() as $field => $messages) {
            $details[$field] = [
                'field' => $field,
                'messages' => $messages,
                'value' => $this->input($field)
            ];
        }
        
        return $details;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim and clean input data
        $this->merge([
            'full_name' => $this->cleanString($this->input('full_name')),
            'email' => strtolower(trim($this->input('email'))),
            'phone' => $this->cleanPhone($this->input('phone')),
            'cover_letter' => $this->cleanString($this->input('cover_letter')),
        ]);
    }

    /**
     * Clean string input
     */
    private function cleanString(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        
        // Remove excessive whitespace and trim
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Clean phone number input
     */
    private function cleanPhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }
        
        // Remove spaces, hyphens, dots, and parentheses
        $phone = preg_replace('/[\s\-\.\(\)]/', '', $phone);
        
        // Convert +84 to 0
        if (str_starts_with($phone, '+84')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '84') && strlen($phone) >= 10) {
            $phone = '0' . substr($phone, 2);
        }
        
        return $phone;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Additional custom validation logic
            $this->validateFileContent($validator);
            $this->validateRateLimit($validator);
        });
    }

    /**
     * Validate file content beyond basic mime type checking
     */
    private function validateFileContent(Validator $validator): void
    {
        if (!$this->hasFile('cv')) {
            return;
        }

        $file = $this->file('cv');
        
        // Additional file validation
        if ($file->getSize() === 0) {
            $validator->errors()->add('cv', 'File CV không được để trống');
        }

        // Check if file is actually readable
        if (!is_readable($file->getPathname())) {
            $validator->errors()->add('cv', 'File CV không thể đọc được');
        }
    }

    /**
     * Basic rate limiting validation
     */
    private function validateRateLimit(Validator $validator): void
    {
        $email = $this->input('email');
        $ip = $this->ip();
        
        // Check applications from same email in last hour
        $recentApplicationsByEmail = \App\Models\JobApplication::where('applicant_email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->count();
            
        if ($recentApplicationsByEmail >= 3) {
            $validator->errors()->add('email', 'Bạn đã gửi quá nhiều đơn ứng tuyển. Vui lòng thử lại sau 1 giờ.');
        }

        // Check applications from same IP in last hour
        $recentApplicationsByIP = \App\Models\JobApplication::whereJsonContains('additional_info->ip_address', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->count();
            
        if ($recentApplicationsByIP >= 5) {
            $validator->errors()->add('general', 'Quá nhiều đơn ứng tuyển từ địa chỉ IP này. Vui lòng thử lại sau.');
        }
    }
}