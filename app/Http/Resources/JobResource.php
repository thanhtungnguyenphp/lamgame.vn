<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'title' => $this->getAttributeValue('name'),
            'slug' => $this->getAttributeValue('url_key'),
            'short_description' => $this->getAttributeValue('short_description'),
            'description' => $this->getAttributeValue('description'),
            
            // Job details
            'job_type' => $this->getAttributeOptionLabel('job_type'),
            'experience_level' => $this->getAttributeOptionLabel('experience_level'),
            'salary_range' => $this->getAttributeOptionLabel('salary_range'),
            'job_location' => $this->getAttributeOptionLabel('job_location'),
            'company_size' => $this->getAttributeOptionLabel('company_size'),
            
            // Requirements
            'required_skills' => $this->getMultiSelectValues('required_skills'),
            'education_level' => $this->getAttributeOptionLabel('education_level'),
            'english_level' => $this->getAttributeOptionLabel('english_level'),
            
            // Benefits & Contact
            'job_benefits' => $this->getMultiSelectValues('job_benefits'),
            'application_deadline' => $this->getFormattedDate('application_deadline'),
            'contact_email' => $this->getAttributeValue('contact_email'),
            'contact_phone' => $this->getAttributeValue('contact_phone'),
            'company_website' => $this->getAttributeValue('company_website'),
            'application_method' => $this->getAttributeOptionLabel('application_method'),
            
            // Boolean flags
            'is_urgent' => $this->getBooleanValue('is_urgent'),
            'is_featured' => $this->getBooleanValue('is_featured'),
            'status' => $this->getBooleanValue('status'),
            
            // Categories
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            
            // SEO
            'meta' => [
                'title' => $this->getAttributeValue('meta_title'),
                'description' => $this->getAttributeValue('meta_description'),
                'keywords' => $this->getAttributeValue('meta_keywords'),
            ],
            
            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Computed fields
            'days_remaining' => $this->getDaysRemaining(),
            'is_expired' => $this->isExpired(),
            'company_info' => $this->getCompanyInfo(),
        ];
    }

    /**
     * Lấy giá trị attribute text
     */
    protected function getAttributeValue(string $code): ?string
    {
        $attributeValue = $this->attribute_values
            ->where('attribute.code', $code)
            ->first();
            
        return $attributeValue?->text_value;
    }

    /**
     * Lấy label của attribute option
     */
    protected function getAttributeOptionLabel(string $code): ?string
    {
        $attributeValue = $this->attribute_values
            ->where('attribute.code', $code)
            ->first();
            
        if (!$attributeValue || !$attributeValue->integer_value) {
            return null;
        }
        
        // Tìm option label từ integer_value (option ID)
        $attribute = \Webkul\Attribute\Models\Attribute::where('code', $code)->first();
        if (!$attribute) return null;
        
        $option = \Webkul\Attribute\Models\AttributeOption::find($attributeValue->integer_value);
        if (!$option) return null;
        
        $translation = $option->translations()->where('locale', 'vi')->first();
        return $translation?->label ?? $option->admin_name;
    }

    /**
     * Lấy values cho multiselect attribute
     */
    protected function getMultiSelectValues(string $code): array
    {
        $attributeValue = $this->attribute_values
            ->where('attribute.code', $code)
            ->first();
            
        if (!$attributeValue || !$attributeValue->text_value) {
            return [];
        }
        
        $optionIds = explode(',', $attributeValue->text_value);
        $labels = [];
        
        foreach ($optionIds as $optionId) {
            if (is_numeric($optionId)) {
                $option = \Webkul\Attribute\Models\AttributeOption::find($optionId);
                if ($option) {
                    $translation = $option->translations()->where('locale', 'vi')->first();
                    $labels[] = $translation?->label ?? $option->admin_name;
                }
            }
        }
        
        return $labels;
    }

    /**
     * Lấy boolean value
     */
    protected function getBooleanValue(string $code): bool
    {
        $attributeValue = $this->attribute_values
            ->where('attribute.code', $code)
            ->first();
            
        return (bool) ($attributeValue?->integer_value ?? false);
    }

    /**
     * Format date
     */
    protected function getFormattedDate(string $code): ?array
    {
        $attributeValue = $this->attribute_values
            ->where('attribute.code', $code)
            ->first();
            
        if (!$attributeValue || !$attributeValue->date_value) {
            return null;
        }
        
        $date = Carbon::parse($attributeValue->date_value);
        
        return [
            'raw' => $attributeValue->date_value,
            'formatted' => $date->format('d/m/Y'),
            'iso' => $date->toISOString(),
            'human' => $date->diffForHumans(),
        ];
    }

    /**
     * Tính số ngày còn lại để apply
     */
    protected function getDaysRemaining(): ?int
    {
        $deadline = $this->getFormattedDate('application_deadline');
        
        if (!$deadline) {
            return null;
        }
        
        $deadlineDate = Carbon::parse($deadline['raw']);
        $today = Carbon::today();
        
        return $deadlineDate->gt($today) ? $today->diffInDays($deadlineDate) : 0;
    }

    /**
     * Kiểm tra job đã hết hạn chưa
     */
    protected function isExpired(): bool
    {
        $deadline = $this->getFormattedDate('application_deadline');
        
        if (!$deadline) {
            return false;
        }
        
        return Carbon::parse($deadline['raw'])->lt(Carbon::today());
    }

    /**
     * Lấy thông tin công ty từ title
     */
    protected function getCompanyInfo(): array
    {
        $title = $this->getAttributeValue('name') ?? '';
        $email = $this->getAttributeValue('contact_email');
        $phone = $this->getAttributeValue('contact_phone');
        $website = $this->getAttributeValue('company_website');
        
        // Extract company name from title (format: "Position - Company Name")
        $parts = explode(' - ', $title);
        $companyName = count($parts) > 1 ? trim($parts[1]) : 'Công ty';
        $position = count($parts) > 1 ? trim($parts[0]) : $title;
        
        return [
            'name' => $companyName,
            'position' => $position,
            'contact' => [
                'email' => $email,
                'phone' => $phone,
                'website' => $website,
            ],
        ];
    }
}