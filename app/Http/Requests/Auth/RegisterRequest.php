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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email:rfc', 
                'max:255', 
                'unique:admins,email'
            ],
            'password' => [
                'required', 
                'string', 
                'min:8',
                'confirmed'
            ],
            'device_name' => ['nullable', 'string', 'max:255'],
            'terms_accepted' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom error messages for validator
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên là bắt buộc.',
            'name.min' => 'Tên phải có ít nhất 2 ký tự.',
            'name.max' => 'Tên không được quá 255 ký tự.',
            
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.max' => 'Email không được quá 255 ký tự.',
            
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            
            
            'terms_accepted.required' => 'Bạn phải đồng ý với điều khoản sử dụng.',
            'terms_accepted.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng.',
            
            'device_name.max' => 'Tên thiết bị không được quá 255 ký tự.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Admin model doesn't have phone field - skip phone processing
        
        // Chuẩn hóa email
        if ($this->has('email')) {
            $this->merge(['email' => strtolower(trim($this->email))]);
        }
        
        // Chuẩn hóa tên
        if ($this->has('name')) {
            $this->merge(['name' => trim($this->name)]);
        }
    }
}
