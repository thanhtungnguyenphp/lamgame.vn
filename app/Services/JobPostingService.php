<?php

namespace App\Services;

use App\Models\JobPosting;
use App\Models\JobPostingSkill;
use App\Models\JobPostingBenefit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JobPostingService
{
    public function create(array $data): JobPosting
    {
        return DB::transaction(function () use ($data) {
            $job = JobPosting::create([
                'title'                => $data['title'],
                'slug'                 => Str::slug($data['title']) . '-' . Str::random(6),
                'description'          => $data['description'] ?? '',
                'short_description'    => $data['short_description'] ?? null,
                'job_type'             => $data['job_type'] ?? null,
                'experience_level'     => $data['experience_level'] ?? null,
                'salary_range'         => $data['salary_range'] ?? null,
                'salary_min'           => $data['salary_min'] ?? null,
                'salary_max'           => $data['salary_max'] ?? null,
                'salary_currency'      => $data['salary_currency'] ?? 'VND',
                'location'             => $data['location'] ?? null,
                'is_remote'            => $data['is_remote'] ?? false,
                'education_level'      => $data['education_level'] ?? null,
                'english_level'        => $data['english_level'] ?? null,
                'company_size'         => $data['company_size'] ?? null,
                'company_id'           => $data['company_id'] ?? null,
                'company_name'         => $data['company_name'] ?? null,
                'company_logo'         => $data['company_logo'] ?? null,
                'contact_email'        => $data['contact_email'] ?? null,
                'contact_phone'        => $data['contact_phone'] ?? null,
                'application_method'   => $data['application_method'] ?? null,
                'application_url'      => $data['application_url'] ?? null,
                'status'               => $data['status'] ?? 'draft',
                'is_featured'          => $data['is_featured'] ?? false,
                'is_urgent'            => $data['is_urgent'] ?? false,
                'application_deadline' => $data['application_deadline'] ?? null,
                'published_at'         => ($data['status'] ?? 'draft') === 'active' ? now() : null,
                'meta_title'           => $data['meta_title'] ?? null,
                'meta_description'     => $data['meta_description'] ?? null,
                'meta_keywords'        => $data['meta_keywords'] ?? null,
                'created_by'           => $data['created_by'] ?? null,
            ]);

            $this->syncSkills($job, $data['skills'] ?? []);
            $this->syncBenefits($job, $data['benefits'] ?? []);

            return $job->load('skills', 'benefits');
        });
    }

    public function update(JobPosting $job, array $data): JobPosting
    {
        return DB::transaction(function () use ($job, $data) {
            $job->update(array_filter($data, fn ($v) => $v !== null));

            if (isset($data['skills'])) {
                $this->syncSkills($job, $data['skills']);
            }
            if (isset($data['benefits'])) {
                $this->syncBenefits($job, $data['benefits']);
            }

            return $job->fresh(['skills', 'benefits']);
        });
    }

    public function delete(JobPosting $job): bool
    {
        return $job->delete();
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = JobPosting::with('skills', 'benefits');

        if ($status = $filters['status'] ?? null) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'active');
        }

        if ($search = $filters['search'] ?? null) {
            $query->search($search);
        }
        if ($type = $filters['job_type'] ?? null) {
            $query->byType($type);
        }
        if ($location = $filters['location'] ?? null) {
            $query->byLocation($location);
        }
        if ($level = $filters['experience_level'] ?? null) {
            $query->where('experience_level', $level);
        }
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }
        if (isset($filters['is_remote'])) {
            $query->where('is_remote', (bool) $filters['is_remote']);
        }
        if ($createdBy = $filters['created_by'] ?? null) {
            $query->where('created_by', $createdBy);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate(min($perPage, 50));
    }

    public function find(int $id): ?JobPosting
    {
        return JobPosting::with('skills', 'benefits')->find($id);
    }

    public function findBySlug(string $slug): ?JobPosting
    {
        return JobPosting::with('skills', 'benefits')->where('slug', $slug)->first();
    }

    public function publish(JobPosting $job): JobPosting
    {
        $job->update(['status' => 'active', 'published_at' => $job->published_at ?? now()]);
        return $job;
    }

    public function unpublish(JobPosting $job): JobPosting
    {
        $job->update(['status' => 'paused']);
        return $job;
    }

    public function duplicate(JobPosting $job, array $overrides = []): JobPosting
    {
        $data = $job->toArray();
        unset($data['id'], $data['slug'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
        $data['title'] = ($overrides['title'] ?? $data['title']) . ' (Copy)';
        $data['status'] = 'draft';
        $data['view_count'] = 0;
        $data['application_count'] = 0;
        $data['click_count'] = 0;
        $data['skills'] = $job->skills->pluck('skill_name')->toArray();
        $data['benefits'] = $job->benefits->pluck('benefit_name')->toArray();

        return $this->create(array_merge($data, $overrides));
    }

    public function getStatistics(?int $createdBy = null): array
    {
        $query = JobPosting::query();
        if ($createdBy) {
            $query->where('created_by', $createdBy);
        }

        return [
            'total'    => (clone $query)->count(),
            'active'   => (clone $query)->where('status', 'active')->count(),
            'draft'    => (clone $query)->where('status', 'draft')->count(),
            'expired'  => (clone $query)->where('status', 'expired')->count(),
            'archived' => (clone $query)->where('status', 'archived')->count(),
            'applications_total' => DB::table('job_applications')
                ->when($createdBy, fn ($q) => $q->whereIn('job_posting_id',
                    JobPosting::where('created_by', $createdBy)->pluck('id')
                ))
                ->count(),
        ];
    }

    public function archiveExpired(): int
    {
        return JobPosting::where('status', 'active')
            ->whereNotNull('application_deadline')
            ->where('application_deadline', '<', now())
            ->update(['status' => 'expired']);
    }

    public function bulkDelete(array $ids, ?int $createdBy = null): int
    {
        $query = JobPosting::whereIn('id', $ids);
        if ($createdBy) {
            $query->where('created_by', $createdBy);
        }
        return $query->delete();
    }

    public function bulkUpdateStatus(array $ids, string $status, ?int $createdBy = null): int
    {
        $query = JobPosting::whereIn('id', $ids);
        if ($createdBy) {
            $query->where('created_by', $createdBy);
        }
        return $query->update(['status' => $status]);
    }

    public function getFilterOptions(): array
    {
        return [
            'job_types'         => JobPosting::distinct()->whereNotNull('job_type')->pluck('job_type'),
            'experience_levels' => JobPosting::distinct()->whereNotNull('experience_level')->pluck('experience_level'),
            'locations'         => JobPosting::distinct()->whereNotNull('location')->pluck('location'),
            'company_sizes'     => JobPosting::distinct()->whereNotNull('company_size')->pluck('company_size'),
            'education_levels'  => JobPosting::distinct()->whereNotNull('education_level')->pluck('education_level'),
            'english_levels'    => JobPosting::distinct()->whereNotNull('english_level')->pluck('english_level'),
            'skills'            => JobPostingSkill::distinct()->pluck('skill_name'),
            'benefits'          => JobPostingBenefit::distinct()->pluck('benefit_name'),
        ];
    }

    // Private helpers

    private function syncSkills(JobPosting $job, array $skills): void
    {
        $job->skills()->delete();
        foreach (array_unique($skills) as $skill) {
            $job->skills()->create(['skill_name' => trim($skill)]);
        }
    }

    private function syncBenefits(JobPosting $job, array $benefits): void
    {
        $job->benefits()->delete();
        foreach (array_unique($benefits) as $benefit) {
            $job->benefits()->create(['benefit_name' => trim($benefit)]);
        }
    }
}
