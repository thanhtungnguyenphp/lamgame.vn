<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'logo_url'       => $this->logo_url,
            'website'        => $this->website,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'employee_count' => $this->employee_count,
            'founded_year'   => $this->founded_year,
            'industry'       => $this->industry,
            'status'         => $this->status,
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),
        ];
    }
}
