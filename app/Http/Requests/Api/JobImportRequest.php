<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class JobImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:10240' // 10MB max file size
            ],
            'mapping' => 'array',
            'mapping.*' => 'string',
            'skip_duplicates' => 'boolean',
            'validate_only' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to import.',
            'file.file' => 'The uploaded file is not valid.',
            'file.mimes' => 'The file must be a CSV or Excel file (csv, txt, xlsx, xls).',
            'file.max' => 'The file size must not exceed 10MB.',
            'mapping.array' => 'The mapping parameter must be an array.',
            'mapping.*.string' => 'Each mapping value must be a string.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'file' => 'import file',
            'mapping' => 'field mapping',
            'skip_duplicates' => 'skip duplicates option',
            'validate_only' => 'validation only option',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional custom validation logic can be added here
            $file = $this->file('file');
            
            if ($file && $file->isValid()) {
                // Check file content type
                $mimeType = $file->getMimeType();
                $allowedMimes = [
                    'text/csv',
                    'text/plain',
                    'application/csv',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];
                
                if (!in_array($mimeType, $allowedMimes)) {
                    $validator->errors()->add('file', 'Invalid file format detected.');
                }

                // Check if file is not empty
                if ($file->getSize() < 10) { // Less than 10 bytes is likely empty
                    $validator->errors()->add('file', 'The uploaded file appears to be empty.');
                }
            }

            // Validate mapping format
            $mapping = $this->input('mapping', []);
            if (!empty($mapping)) {
                $validTargetFields = [
                    'name', 'description', 'short_description', 'location',
                    'salary_min', 'salary_max', 'job_type', 'experience_level',
                    'skills', 'requirements', 'benefits', 'application_deadline',
                    'status', 'is_featured', 'category', 'company_name', 'contact_email'
                ];
                
                foreach ($mapping as $sourceField => $targetField) {
                    if (!in_array($targetField, $validTargetFields)) {
                        $validator->errors()->add(
                            "mapping.{$sourceField}",
                            "Invalid target field '{$targetField}' in mapping."
                        );
                    }
                }
            }
        });
    }
}