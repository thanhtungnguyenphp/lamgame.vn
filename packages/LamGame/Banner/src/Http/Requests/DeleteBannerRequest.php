<?php

namespace LamGame\Banner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use LamGame\Banner\Models\Banner;

class DeleteBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Basic authorization - can be extended based on your auth system
        // For now, allow all authenticated users to delete banners
        // In production, you might want to check specific permissions
        return true;
        
        // Example with permission check:
        // return $this->user() && $this->user()->can('delete', Banner::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Banner ID is already validated by route constraint [0-9]+
            // But we can add additional validation here if needed
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'id' => 'banner ID',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Banner ID is required',
            'id.integer' => 'Banner ID must be a valid number',
            'id.min' => 'Banner ID must be greater than 0',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $bannerId = $this->route('id');
            
            // Check if banner exists
            if ($bannerId && !Banner::where('id', $bannerId)->exists()) {
                $validator->errors()->add('id', 'Banner not found');
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation($validator): void
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization(): void
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this banner',
            ], Response::HTTP_FORBIDDEN)
        );
    }
}