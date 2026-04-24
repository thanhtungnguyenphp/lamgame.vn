<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Migrate job data from products (EAV) → job_postings (flat table).
 * Run AFTER 2026_04_24_000001_create_job_postings_table.
 */
return new class extends Migration
{
    // Attribute option ID → label mapping
    private array $optionMap = [];

    public function up(): void
    {
        // Build option map
        $options = DB::table('attribute_options as ao')
            ->join('attribute_option_translations as aot', function ($j) {
                $j->on('ao.id', '=', 'aot.attribute_option_id')->where('aot.locale', 'vi');
            })
            ->select('ao.id', 'aot.label')
            ->get();
        foreach ($options as $o) {
            $this->optionMap[$o->id] = $o->label;
        }

        // Get all job products
        $jobs = DB::table('products as p')
            ->join('product_flat as pf', function ($j) {
                $j->on('p.id', '=', 'pf.product_id')->where('pf.locale', 'vi');
            })
            ->where('p.type', 'job')
            ->select('p.id', 'p.sku', 'pf.name', 'pf.short_description', 'pf.description', 'pf.url_key', 'pf.status', 'pf.new', 'pf.featured', 'p.created_at', 'p.updated_at')
            ->get();

        foreach ($jobs as $job) {
            $attrs = $this->getJobAttributes($job->id);

            $postingId = DB::table('job_postings')->insertGetId([
                'title'                => $job->name,
                'slug'                 => $job->url_key ?: \Illuminate\Support\Str::slug($job->name) . '-' . $job->id,
                'description'          => $job->description ?? '',
                'short_description'    => $job->short_description,
                'job_type'             => $this->resolveOption($attrs['job_type'] ?? null),
                'experience_level'     => $this->resolveOption($attrs['experience_level'] ?? null),
                'salary_range'         => $this->resolveOption($attrs['salary_range'] ?? null),
                'location'             => $this->resolveOption($attrs['job_location'] ?? null),
                'is_remote'            => ($this->resolveOption($attrs['job_location'] ?? null) === 'Remote'),
                'education_level'      => $this->resolveOption($attrs['education_level'] ?? null),
                'english_level'        => $this->resolveOption($attrs['english_level'] ?? null),
                'company_size'         => $this->resolveOption($attrs['company_size'] ?? null),
                'contact_email'        => $attrs['contact_email'] ?? null,
                'contact_phone'        => $attrs['contact_phone'] ?? null,
                'application_method'   => $this->resolveOption($attrs['application_method'] ?? null),
                'status'               => $job->status ? 'active' : 'draft',
                'is_featured'          => (bool) ($job->featured ?? false),
                'is_urgent'            => (bool) ($job->new ?? false),
                'application_deadline' => $this->resolveDeadline($attrs['application_deadline'] ?? null),
                'published_at'         => $job->status ? $job->created_at : null,
                'created_by'           => 1,
                'created_at'           => $job->created_at,
                'updated_at'           => $job->updated_at,
            ]);

            // Migrate skills
            $skillIds = $this->parseMultiSelect($attrs['required_skills'] ?? null);
            foreach ($skillIds as $sid) {
                $label = $this->optionMap[$sid] ?? null;
                if ($label) {
                    DB::table('job_posting_skills')->insert([
                        'job_posting_id' => $postingId,
                        'skill_name'     => $label,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }

            // Migrate benefits
            $benefitIds = $this->parseMultiSelect($attrs['job_benefits'] ?? null);
            foreach ($benefitIds as $bid) {
                $label = $this->optionMap[$bid] ?? null;
                if ($label) {
                    DB::table('job_posting_benefits')->insertOrIgnore([
                        'job_posting_id' => $postingId,
                        'benefit_name'   => $label,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }

            // Link existing applications
            DB::table('job_applications')
                ->where('job_id', $job->id)
                ->update(['job_posting_id' => $postingId]);
        }
    }

    public function down(): void
    {
        DB::table('job_applications')->update(['job_posting_id' => null]);
        DB::table('job_posting_benefits')->truncate();
        DB::table('job_posting_skills')->truncate();
        DB::table('job_postings')->truncate();
    }

    private function getJobAttributes(int $productId): array
    {
        $rows = DB::table('product_attribute_values as pav')
            ->join('attributes as a', 'a.id', '=', 'pav.attribute_id')
            ->where('pav.product_id', $productId)
            ->where(function ($q) {
                $q->where('pav.locale', 'vi')->orWhereNull('pav.locale');
            })
            ->whereIn('a.code', [
                'job_type', 'experience_level', 'salary_range', 'job_location',
                'company_size', 'required_skills', 'education_level', 'english_level',
                'job_benefits', 'application_method', 'application_deadline',
                'contact_email', 'contact_phone',
            ])
            ->select('a.code', 'pav.text_value', 'pav.integer_value', 'pav.boolean_value', 'pav.datetime_value')
            ->get();

        $attrs = [];
        foreach ($rows as $r) {
            $attrs[$r->code] = $r->text_value ?? $r->integer_value ?? $r->datetime_value;
        }
        return $attrs;
    }

    private function resolveOption($value): ?string
    {
        if (!$value || !is_numeric($value)) return $value;
        return $this->optionMap[(int) $value] ?? null;
    }

    private function resolveDeadline($value): ?string
    {
        if (!$value) return null;
        if (is_numeric($value)) return null; // option_id, not a date
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseMultiSelect($value): array
    {
        if (!$value) return [];
        return array_filter(array_map('intval', explode(',', $value)));
    }
};
