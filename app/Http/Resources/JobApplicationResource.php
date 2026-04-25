<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'application_code' => $this->application_code,
            'status'           => $this->status,

            // Ứng viên
            'applicant_name'   => $this->applicant_name,
            'applicant_email'  => $this->applicant_email,
            'applicant_phone'  => $this->applicant_phone,
            'cover_letter'     => $this->cover_letter,
            'resume_file_path' => $this->resume_file_path,
            'additional_info'  => $this->additional_info,

            // Ghi chú employer
            'employer_notes'   => $this->employer_notes,

            // Job liên quan
            'job' => $this->whenLoaded('jobPosting', fn () => [
                'id'           => $this->jobPosting->id,
                'title'        => $this->jobPosting->title,
                'slug'         => $this->jobPosting->slug,
                'company_name' => $this->jobPosting->company_name,
            ]),

            'applied_at'  => $this->applied_at?->toIso8601String(),
            'created_at'  => $this->created_at->toIso8601String(),
            'updated_at'  => $this->updated_at->toIso8601String(),
        ];
    }
}
