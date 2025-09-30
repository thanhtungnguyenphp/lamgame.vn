<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id)
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:11'
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif',
                'max:2048'
            ],
        ];

        // Yêu cầu mật khẩu hiện tại khi thay đổi email
        if ($this->email !== $this->user()->email) {
            $rules['current_password'] = ['required', 'string', 'current_password'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'name.min' => 'Tên phải có ít nhất :min ký tự.',
            'name.max' => 'Tên không được vượt quá :max ký tự.',
            
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'phone.min' => 'Số điện thoại phải có ít nhất :min số.',
            'phone.max' => 'Số điện thoại không được vượt quá :max số.',
            
            'bio.max' => 'Giới thiệu không được vượt quá :max ký tự.',
            
            'avatar.image' => 'File phải là ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại để thay đổi email.',
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
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
}