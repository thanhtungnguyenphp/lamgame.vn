<?php

namespace App\Services;

class AiJobDescriptionOptimizer
{
    private AiChatGptService $chatGptService;

    public function __construct(AiChatGptService $chatGptService)
    {
        $this->chatGptService = $chatGptService;
    }

    public function optimizeJobPosting(array $payload): array
    {
        try {
            // Use ChatGPT for AI optimization
            return $this->chatGptService->optimizeJobDescription($payload);
        } catch (\Exception $e) {
            // Fallback to basic optimization if AI fails
            return $this->basicOptimization($payload);
        }
    }

    private function basicOptimization(array $payload): array
    {
        return [
            'title' => $this->optimizeTitle($payload['title'] ?? ''),
            'description' => $this->optimizeDescription($payload),
            'requirements' => $this->extractRequirements($payload),
            'benefits' => $this->extractBenefits($payload),
            'skills' => $this->extractSkills($payload),
            'salary_range' => $this->optimizeSalary($payload),
            'location' => $this->optimizeLocation($payload['location'] ?? ''),
            'job_type' => $this->determineJobType($payload),
            'experience_level' => $this->determineExperienceLevel($payload)
        ];
    }

    private function optimizeTitle(string $title): string
    {
        return trim(ucwords(strtolower($title)));
    }

    private function optimizeDescription(array $payload): string
    {
        $description = $payload['description'] ?? '';
        $company = $payload['company'] ?? '';
        
        if (empty($description) && !empty($company)) {
            return "Join {$company} team in this exciting opportunity.";
        }
        
        return $description;
    }

    private function extractRequirements(array $payload): array
    {
        $requirements = [];
        
        if (!empty($payload['requirements'])) {
            $requirements = explode(',', $payload['requirements']);
        }
        
        if (!empty($payload['experience'])) {
            $requirements[] = $payload['experience'] . ' years of experience';
        }
        
        return array_map('trim', $requirements);
    }

    private function extractBenefits(array $payload): array
    {
        $benefits = [];
        
        if (!empty($payload['benefits'])) {
            $benefits = explode(',', $payload['benefits']);
        }
        
        return array_map('trim', $benefits);
    }

    private function extractSkills(array $payload): array
    {
        $skills = [];
        
        if (!empty($payload['skills'])) {
            $skills = explode(',', $payload['skills']);
        }
        
        return array_map('trim', $skills);
    }

    private function optimizeSalary(array $payload): ?string
    {
        if (!empty($payload['salary_min']) && !empty($payload['salary_max'])) {
            return $payload['salary_min'] . ' - ' . $payload['salary_max'];
        }
        
        return $payload['salary'] ?? null;
    }

    private function optimizeLocation(string $location): string
    {
        return trim($location) ?: 'Remote';
    }

    private function determineJobType(array $payload): string
    {
        return $payload['job_type'] ?? 'Full-time';
    }

    private function determineExperienceLevel(array $payload): string
    {
        $experience = (int)($payload['experience'] ?? 0);
        
        if ($experience <= 1) return 'Entry Level';
        if ($experience <= 3) return 'Mid Level';
        if ($experience <= 7) return 'Senior Level';
        
        return 'Executive Level';
    }
}
