<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'short_description'    => 'nullable|string|max:500',
            'job_type'             => 'nullable|string|max:50',
            'experience_level'     => 'nullable|string|max:50',
            'salary_range'         => 'nullable|string',
            'salary_min'           => 'nullable|numeric|min:0',
            'salary_max'           => 'nullable|numeric|min:0|gte:salary_min',
            'location'             => 'nullable|string',
            'is_remote'            => 'nullable|boolean',
            'education_level'      => 'nullable|string|max:50',
            'english_level'        => 'nullable|string|max:50',
            'company_name'         => 'nullable|string|max:255',
            'company_size'         => 'nullable|string|max:50',
            'contact_email'        => 'nullable|email',
            'contact_phone'        => 'nullable|string|max:20',
            'application_method'   => 'nullable|string',
            'application_url'      => 'nullable|url',
            'application_deadline' => 'nullable|date|after:today',
            'is_featured'          => 'nullable|boolean',
            'is_urgent'            => 'nullable|boolean',
            'status'               => 'nullable|in:draft,active',
            'skills'               => 'nullable|array|max:20',
            'skills.*'             => 'string|max:100',
            'benefits'             => 'nullable|array|max:20',
            'benefits.*'           => 'string|max:100',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string|max:500',
        ];
    }
}
