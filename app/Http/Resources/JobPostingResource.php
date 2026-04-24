<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobPostingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'slug'                  => $this->slug,
            'description'           => $this->description,
            'short_description'     => $this->short_description,
            'url'                   => $this->url,

            // Job details
            'job_type'              => $this->job_type,
            'experience_level'      => $this->experience_level,
            'salary_range'          => $this->salary_range,
            'salary_min'            => $this->salary_min,
            'salary_max'            => $this->salary_max,
            'salary_currency'       => $this->salary_currency,
            'location'              => $this->location,
            'is_remote'             => $this->is_remote,

            // Requirements
            'education_level'       => $this->education_level,
            'english_level'         => $this->english_level,
            'skills'                => $this->whenLoaded('skills', fn () => $this->skills->pluck('skill_name')),
            'benefits'              => $this->whenLoaded('benefits', fn () => $this->benefits->pluck('benefit_name')),

            // Company
            'company_name'          => $this->company_name,
            'company_size'          => $this->company_size,
            'company_logo'          => $this->company_logo,

            // Contact
            'contact_email'         => $this->contact_email,
            'contact_phone'         => $this->contact_phone,
            'application_method'    => $this->application_method,
            'application_url'       => $this->application_url,

            // Status
            'status'                => $this->status,
            'is_featured'           => $this->is_featured,
            'is_urgent'             => $this->is_urgent,
            'application_deadline'  => $this->application_deadline?->format('Y-m-d'),
            'days_remaining'        => $this->daysRemaining(),
            'is_expired'            => $this->isExpired(),

            // Stats
            'view_count'            => $this->view_count,
            'application_count'     => $this->application_count,
            'click_count'           => $this->click_count,

            // SEO
            'meta_title'            => $this->meta_title,
            'meta_description'      => $this->meta_description,
            'meta_keywords'         => $this->meta_keywords,

            // Timestamps
            'published_at'          => $this->published_at?->toIso8601String(),
            'created_at'            => $this->created_at->toIso8601String(),
            'updated_at'            => $this->updated_at->toIso8601String(),
        ];
    }
}
