<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Carbon\Carbon;

class ExtendedProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by auth:sanctum middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'date_of_birth' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'gender' => ['nullable', 'in:male,female,other'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s\(\)]+$/'],
            
            // Address Information
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            
            // Professional Information
            'bio' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:100'],
            
            // Social Links
            'social_links' => ['nullable', 'array'],
            'social_links.facebook' => ['nullable', 'url'],
            'social_links.twitter' => ['nullable', 'url'],
            'social_links.linkedin' => ['nullable', 'url'],
            'social_links.instagram' => ['nullable', 'url'],
            'social_links.youtube' => ['nullable', 'url'],
            'social_links.tiktok' => ['nullable', 'url'],
            
            // User Preferences
            'preferences' => ['nullable', 'array'],
            'preferences.language' => ['nullable', 'string', 'in:vi,en'],
            'preferences.timezone' => ['nullable', 'string', 'timezone'],
            'preferences.date_format' => ['nullable', 'string', 'in:d/m/Y,m/d/Y,Y-m-d'],
            'preferences.time_format' => ['nullable', 'string', 'in:H:i,h:i A'],
            'preferences.notifications' => ['nullable', 'array'],
            'preferences.privacy' => ['nullable', 'array'],
            
            // Emergency Contact
            'emergency_contact' => ['nullable', 'array'],
            'emergency_contact.name' => ['required_with:emergency_contact', 'string', 'max:100'],
            'emergency_contact.phone' => ['required_with:emergency_contact', 'string', 'max:20'],
            'emergency_contact.relationship' => ['nullable', 'string', 'max:50'],
            
            // Privacy Settings
            'is_public' => ['nullable', 'boolean'],
            
            // Custom Fields for future extensibility
            'custom_fields' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Personal Information
            'date_of_birth.date' => 'Ngày sinh không đúng định dạng.',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'date_of_birth.after' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính chỉ có thể là: nam, nữ, hoặc khác.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'phone.max' => 'Số điện thoại quá dài.',
            
            // Address Information
            'address.max' => 'Địa chỉ không được quá 500 ký tự.',
            'city.max' => 'Tên thành phố không được quá 100 ký tự.',
            'state.max' => 'Tên tỉnh/bang không được quá 100 ký tự.',
            'country.max' => 'Tên quốc gia không được quá 100 ký tự.',
            'postal_code.max' => 'Mã bưu chính không được quá 20 ký tự.',
            
            // Professional Information
            'bio.max' => 'Tiểu sử không được quá 1000 ký tự.',
            'website.url' => 'Website phải là URL hợp lệ.',
            'website.max' => 'Đường link website quá dài.',
            'job_title.max' => 'Chức danh không được quá 100 ký tự.',
            'company.max' => 'Tên công ty không được quá 100 ký tự.',
            
            // Social Links
            'social_links.facebook.url' => 'Facebook URL không hợp lệ.',
            'social_links.twitter.url' => 'Twitter URL không hợp lệ.',
            'social_links.linkedin.url' => 'LinkedIn URL không hợp lệ.',
            'social_links.instagram.url' => 'Instagram URL không hợp lệ.',
            'social_links.youtube.url' => 'YouTube URL không hợp lệ.',
            'social_links.tiktok.url' => 'TikTok URL không hợp lệ.',
            
            // Preferences
            'preferences.language.in' => 'Ngôn ngữ chỉ có thể là tiếng Việt hoặc tiếng Anh.',
            'preferences.timezone.timezone' => 'Múi giờ không hợp lệ.',
            'preferences.date_format.in' => 'Định dạng ngày không hợp lệ.',
            'preferences.time_format.in' => 'Định dạng giờ không hợp lệ.',
            
            // Emergency Contact
            'emergency_contact.name.required_with' => 'Tên liên hệ khẩn cấp là bắt buộc.',
            'emergency_contact.phone.required_with' => 'Số điện thoại liên hệ khẩn cấp là bắt buộc.',
            'emergency_contact.name.max' => 'Tên liên hệ khẩn cấp không được quá 100 ký tự.',
            'emergency_contact.phone.max' => 'Số điện thoại liên hệ khẩn cấp không được quá 20 ký tự.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dữ liệu không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Bạn không có quyền thực hiện thao tác này.',
            'errors' => [
                'authorization' => ['Unauthorized.']
            ]
        ], 403));
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation for Vietnamese phone numbers
            if ($this->phone) {
                if (!$this->isValidVietnamesePhone($this->phone)) {
                    $validator->errors()->add('phone', 'Số điện thoại Việt Nam không hợp lệ.');
                }
            }
            
            // Validate age (must be at least 16 years old)
            if ($this->date_of_birth) {
                $age = Carbon::parse($this->date_of_birth)->age;
                if ($age < 16) {
                    $validator->errors()->add('date_of_birth', 'Bạn phải từ 16 tuổi trở lên.');
                }
                if ($age > 120) {
                    $validator->errors()->add('date_of_birth', 'Tuổi không hợp lệ.');
                }
            }
        });
    }

    /**
     * Validate Vietnamese phone number format.
     *
     * @param string $phone
     * @return bool
     */
    private function isValidVietnamesePhone(string $phone): bool
    {
        // Remove all non-digit characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Vietnamese mobile patterns
        $patterns = [
            '/^(84|0)(3[2-9]|5[6|8|9]|7[0|6-9]|8[1-9]|9[0-9])[0-9]{7}$/', // Mobile
            '/^(84|0)(2[0-9])[0-9]{8}$/', // Landline
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleanPhone)) {
                return true;
            }
        }
        
        return false;
    }
}
