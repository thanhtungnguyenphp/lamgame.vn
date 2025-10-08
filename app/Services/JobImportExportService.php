<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Admin;
use App\Models\JobImportLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JobsExport;
use App\Imports\JobsImport;
use Barryvdh\DomPDF\Facade\Pdf;

class JobImportExportService
{
    protected JobService $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Import jobs from uploaded file
     */
    public function importJobs(
        UploadedFile $file,
        Admin $user,
        array $mapping = [],
        bool $skipDuplicates = true,
        bool $validateOnly = false
    ): array {
        $importId = Str::uuid();
        $results = [
            'import_id' => $importId,
            'total' => 0,
            'imported' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        try {
            DB::beginTransaction();

            // Store file temporarily
            $filePath = $file->store('imports', 'local');
            
            // Read file data
            $data = $this->readImportFile($file, $mapping);
            $results['total'] = count($data);
            $results['total_rows'] = $results['total'];
            $results['valid_rows'] = 0;
            $results['invalid_rows'] = 0;

            if ($validateOnly) {
                $validationResults = $this->validateImportData($data, $user);
                $results = array_merge($results, $validationResults);
                DB::rollBack();
                return $results;
            }

            // Process each row
            foreach ($data as $index => $row) {
                try {
                    // Validate row data
                    $validation = $this->validateJobRow($row, $user);
                    if (!$validation['valid']) {
                        $results['failed']++;
                        $results['errors'][] = [
                            'row' => $index + 1,
                            'errors' => $validation['errors']
                        ];
                        continue;
                    }

                    // Check for duplicates
                    if ($skipDuplicates && $this->isDuplicateJob($row, $user)) {
                        $results['skipped']++;
                        continue;
                    }

                    // Create job
                    $jobData = $this->mapImportDataToJob($row, $user);
                    $job = $this->jobService->create($jobData, $user);
                    
                    if ($job) {
                        $results['imported']++;
                    } else {
                        $results['failed']++;
                        $results['errors'][] = [
                            'row' => $index + 1,
                            'errors' => ['Failed to create job']
                        ];
                    }

                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $index + 1,
                        'errors' => [$e->getMessage()]
                    ];
                }
            }

            // Log import
            $this->logImport($importId, $user, $file->getClientOriginalName(), $results);

            DB::commit();

            // Clean up temporary file
            Storage::disk('local')->delete($filePath);

            return $results;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Export jobs to specified format
     */
    public function exportJobs(
        Collection $jobs,
        string $format = 'csv',
        bool $includeApplications = false,
        bool $includeStatistics = false
    ): array {
        $filename = 'jobs_export_' . date('Y-m-d_H-i-s');
        $exportData = $this->prepareExportData($jobs, $includeApplications, $includeStatistics);

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($exportData, $filename);
            case 'excel':
                return $this->exportToExcel($exportData, $filename);
            case 'pdf':
                return $this->exportToPdf($exportData, $filename);
            default:
                throw new \InvalidArgumentException('Unsupported export format');
        }
    }

    /**
     * Generate import template
     */
    public function generateImportTemplate(string $format = 'csv', bool $includeExamples = true): array
    {
        $headers = $this->getImportHeaders();
        $exampleData = $includeExamples ? $this->getExampleImportData() : [];

        $filename = 'job_import_template_' . date('Y-m-d');

        if ($format === 'csv') {
            return $this->generateCsvTemplate($headers, $exampleData, $filename);
        } else {
            return $this->generateExcelTemplate($headers, $exampleData, $filename);
        }
    }

    /**
     * Preview import data
     */
    public function previewImportData(UploadedFile $file, array $mapping = [], int $rowsToPreview = 10): array
    {
        $data = $this->readImportFile($file, $mapping, $rowsToPreview);
        $headers = $this->detectFileHeaders($file);
        
        return [
            'headers' => $headers,
            'sample_data' => array_slice($data, 0, $rowsToPreview),
            'total_rows' => $this->countFileRows($file),
            'suggested_mapping' => $this->suggestFieldMapping($headers),
            'validation_summary' => $this->validateImportData(array_slice($data, 0, 50))
        ];
    }

    /**
     * Get field mapping options
     */
    public function getFieldMappingOptions(): array
    {
        return [
            'required_fields' => [
                'name' => 'Job Title/Name *',
                'description' => 'Job Description *',
                'location' => 'Location *',
                'salary_min' => 'Minimum Salary',
                'salary_max' => 'Maximum Salary'
            ],
            'optional_fields' => [
                'short_description' => 'Short Description',
                'requirements' => 'Job Requirements',
                'benefits' => 'Benefits',
                'employment_type' => 'Employment Type (full-time, part-time, contract)',
                'experience_level' => 'Experience Level (entry, mid, senior)',
                'skills' => 'Required Skills (comma separated)',
                'application_deadline' => 'Application Deadline (YYYY-MM-DD)',
                'status' => 'Status (active, inactive)',
                'is_featured' => 'Featured (yes/no)',
                'category' => 'Job Category',
                'company_name' => 'Company Name',
                'contact_email' => 'Contact Email'
            ],
            'date_formats' => [
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
                'Y-m-d H:i:s'
            ],
            'boolean_values' => [
                'true_values' => ['yes', 'true', '1', 'active', 'enabled'],
                'false_values' => ['no', 'false', '0', 'inactive', 'disabled']
            ]
        ];
    }

    /**
     * Get import history
     */
    public function getImportHistory(Admin $user, int $page = 1, int $perPage = 15): array
    {
        $history = JobImportLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'imports' => $history->items(),
            'pagination' => [
                'current_page' => $history->currentPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
                'last_page' => $history->lastPage(),
                'has_more' => $history->hasMorePages()
            ]
        ];
    }

    /**
     * Read import file and convert to array
     */
    protected function readImportFile(UploadedFile $file, array $mapping = [], int $limit = null): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, ['csv', 'txt'])) {
            return $this->readCsvFile($file, $mapping, $limit);
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            return $this->readExcelFile($file, $mapping, $limit);
        }
        
        throw new \InvalidArgumentException('Unsupported file format');
    }

    /**
     * Read CSV file
     */
    protected function readCsvFile(UploadedFile $file, array $mapping = [], int $limit = null): array
    {
        $data = [];
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            fclose($handle);
            throw new \InvalidArgumentException('Invalid CSV file format');
        }

        $rowCount = 0;
        while (($row = fgetcsv($handle)) !== false && ($limit === null || $rowCount < $limit)) {
            $rowData = array_combine($headers, $row);
            $data[] = $this->applyMapping($rowData, $mapping);
            $rowCount++;
        }
        
        fclose($handle);
        return $data;
    }

    /**
     * Read Excel file
     */
    protected function readExcelFile(UploadedFile $file, array $mapping = [], int $limit = null): array
    {
        $import = new JobsImport($mapping, $limit);
        return Excel::toArray($import, $file)[0] ?? [];
    }

    /**
     * Apply field mapping to row data
     */
    protected function applyMapping(array $rowData, array $mapping): array
    {
        if (empty($mapping)) {
            return $rowData;
        }

        $mappedData = [];
        foreach ($mapping as $sourceField => $targetField) {
            if (isset($rowData[$sourceField])) {
                $mappedData[$targetField] = $rowData[$sourceField];
            }
        }

        return $mappedData;
    }

    /**
     * Validate import data
     */
    protected function validateImportData(array $data, Admin $user = null): array
    {
        $validRows = 0;
        $invalidRows = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            $validation = $this->validateJobRow($row, $user);
            if ($validation['valid']) {
                $validRows++;
            } else {
                $invalidRows++;
                $errors[] = [
                    'row' => $index + 1,
                    'errors' => $validation['errors']
                ];
            }
        }

        return [
            'valid_rows' => $validRows,
            'invalid_rows' => $invalidRows,
            'errors' => $errors
        ];
    }

    /**
     * Validate individual job row
     */
    protected function validateJobRow(array $row, Admin $user = null): array
    {
        $errors = [];

        // Required fields
        if (empty($row['name'] ?? '')) {
            $errors[] = 'Job name/title is required';
        }
        
        if (empty($row['description'] ?? '')) {
            $errors[] = 'Job description is required';
        }

        // Validate salary
        if (!empty($row['salary_min']) && !is_numeric($row['salary_min'])) {
            $errors[] = 'Minimum salary must be numeric';
        }

        if (!empty($row['salary_max']) && !is_numeric($row['salary_max'])) {
            $errors[] = 'Maximum salary must be numeric';
        }

        // Validate date
        if (!empty($row['application_deadline'])) {
            $deadline = $this->parseDate($row['application_deadline']);
            if (!$deadline) {
                $errors[] = 'Invalid application deadline format';
            } elseif ($deadline->isPast()) {
                $errors[] = 'Application deadline cannot be in the past';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Check if job is duplicate
     */
    protected function isDuplicateJob(array $row, Admin $user): bool
    {
        return Product::where('admin_id', $user->id)
            ->where('name', $row['name'] ?? '')
            ->where('description', $row['description'] ?? '')
            ->exists();
    }

    /**
     * Map import data to job format
     */
    protected function mapImportDataToJob(array $row, Admin $user): array
    {
        $jobData = [
            'name' => $row['name'] ?? '',
            'description' => $row['description'] ?? '',
            'short_description' => $row['short_description'] ?? '',
            'status' => $this->parseBoolean($row['status'] ?? 'active') ? 1 : 0,
            'admin_id' => $user->id,
            'location' => $row['location'] ?? '',
            'salary_min' => !empty($row['salary_min']) ? floatval($row['salary_min']) : null,
            'salary_max' => !empty($row['salary_max']) ? floatval($row['salary_max']) : null,
        ];

        // Handle application deadline
        if (!empty($row['application_deadline'])) {
            $deadline = $this->parseDate($row['application_deadline']);
            if ($deadline) {
                $jobData['application_deadline'] = $deadline->format('Y-m-d H:i:s');
            }
        }

        return $jobData;
    }

    /**
     * Parse date from various formats
     */
    protected function parseDate(string $dateString): ?Carbon
    {
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y-m-d H:i:s'];
        
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $dateString);
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return null;
    }

    /**
     * Parse boolean value
     */
    protected function parseBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        
        $trueValues = ['yes', 'true', '1', 'active', 'enabled', 'on'];
        return in_array(strtolower(trim($value)), $trueValues);
    }

    /**
     * Prepare data for export
     */
    protected function prepareExportData(Collection $jobs, bool $includeApplications = false, bool $includeStatistics = false): array
    {
        $data = [];
        
        foreach ($jobs as $job) {
            $jobData = [
                'id' => $job->id,
                'name' => $job->name,
                'description' => strip_tags($job->description),
                'short_description' => $job->short_description,
                'location' => $job->location,
                'salary_min' => $job->salary_min,
                'salary_max' => $job->salary_max,
                'status' => $job->status ? 'Active' : 'Inactive',
                'created_at' => $job->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $job->updated_at->format('Y-m-d H:i:s'),
            ];

            if ($includeStatistics) {
                $stats = $this->jobService->getJobStatistics($job);
                $jobData = array_merge($jobData, [
                    'views' => $stats['views'] ?? 0,
                    'applications' => $stats['applications'] ?? 0,
                    'conversion_rate' => $stats['conversion_rate'] ?? 0
                ]);
            }

            $data[] = $jobData;
        }

        return $data;
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv(array $data, string $filename): array
    {
        $filepath = storage_path('app/exports/' . $filename . '.csv');
        
        // Ensure directory exists
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $file = fopen($filepath, 'w');
        
        if (!empty($data)) {
            // Write headers
            fputcsv($file, array_keys($data[0]));
            
            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
        }
        
        fclose($file);

        return [
            'file_path' => $filepath,
            'filename' => $filename . '.csv',
            'download_url' => url('api/exports/' . $filename . '.csv'),
            'expires_at' => now()->addHours(24),
            'headers' => ['Content-Type' => 'text/csv']
        ];
    }

    /**
     * Export to Excel
     */
    protected function exportToExcel(array $data, string $filename): array
    {
        $filepath = storage_path('app/exports/' . $filename . '.xlsx');
        
        Excel::store(new JobsExport($data), 'exports/' . $filename . '.xlsx');

        return [
            'file_path' => $filepath,
            'filename' => $filename . '.xlsx',
            'download_url' => url('api/exports/' . $filename . '.xlsx'),
            'expires_at' => now()->addHours(24),
            'headers' => ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        ];
    }

    /**
     * Export to PDF
     */
    protected function exportToPdf(array $data, string $filename): array
    {
        $filepath = storage_path('app/exports/' . $filename . '.pdf');
        
        $pdf = Pdf::loadView('exports.jobs', compact('data'));
        $pdf->save($filepath);

        return [
            'file_path' => $filepath,
            'filename' => $filename . '.pdf',
            'download_url' => url('api/exports/' . $filename . '.pdf'),
            'expires_at' => now()->addHours(24),
            'headers' => ['Content-Type' => 'application/pdf']
        ];
    }

    /**
     * Get import headers template
     */
    protected function getImportHeaders(): array
    {
        return [
            'name',
            'description', 
            'short_description',
            'location',
            'salary_min',
            'salary_max',
            'employment_type',
            'experience_level',
            'skills',
            'requirements',
            'benefits',
            'application_deadline',
            'status',
            'is_featured',
            'category',
            'company_name',
            'contact_email'
        ];
    }

    /**
     * Get example import data
     */
    protected function getExampleImportData(): array
    {
        return [
            [
                'name' => 'Senior PHP Developer',
                'description' => 'We are looking for an experienced PHP developer to join our team...',
                'short_description' => 'Senior PHP Developer position with competitive salary',
                'location' => 'Ho Chi Minh City',
                'salary_min' => '2000',
                'salary_max' => '3000',
                'employment_type' => 'full-time',
                'experience_level' => 'senior',
                'skills' => 'PHP, Laravel, MySQL, JavaScript',
                'requirements' => '3+ years PHP experience, Laravel framework knowledge',
                'benefits' => 'Health insurance, flexible working hours, annual bonus',
                'application_deadline' => date('Y-m-d', strtotime('+30 days')),
                'status' => 'active',
                'is_featured' => 'no',
                'category' => 'Information Technology',
                'company_name' => 'Tech Company Ltd',
                'contact_email' => 'hr@techcompany.com'
            ]
        ];
    }

    // Additional helper methods...
    protected function generateCsvTemplate(array $headers, array $exampleData, string $filename): array
    {
        $filepath = storage_path('app/templates/' . $filename . '.csv');
        
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $file = fopen($filepath, 'w');
        fputcsv($file, $headers);
        
        foreach ($exampleData as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);

        return [
            'file_path' => $filepath,
            'filename' => $filename . '.csv',
            'download_url' => url('api/templates/' . $filename . '.csv'),
            'expires_at' => now()->addHours(24),
            'headers' => ['Content-Type' => 'text/csv']
        ];
    }

    protected function generateExcelTemplate(array $headers, array $exampleData, string $filename): array
    {
        // Implementation for Excel template generation
        // Similar to generateCsvTemplate but for Excel format
        $filepath = storage_path('app/templates/' . $filename . '.xlsx');
        
        // Use Excel facade to create template
        Excel::store(new JobsExport([$exampleData[0] ?? array_fill_keys($headers, '')]), 'templates/' . $filename . '.xlsx');

        return [
            'file_path' => $filepath,
            'filename' => $filename . '.xlsx',
            'download_url' => url('api/templates/' . $filename . '.xlsx'),
            'expires_at' => now()->addHours(24),
            'headers' => ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        ];
    }

    protected function detectFileHeaders(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            $headers = fgetcsv($handle);
            fclose($handle);
            return $headers ?: [];
        }
        
        // For Excel files, read first row
        return []; // Implement Excel header detection
    }

    protected function countFileRows(UploadedFile $file): int
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, ['csv', 'txt'])) {
            $count = 0;
            $handle = fopen($file->getRealPath(), 'r');
            while (fgetcsv($handle) !== false) {
                $count++;
            }
            fclose($handle);
            return $count - 1; // Exclude header
        }
        
        return 0; // Implement Excel row counting
    }

    protected function suggestFieldMapping(array $headers): array
    {
        $suggestions = [];
        $standardFields = $this->getFieldMappingOptions()['required_fields'] + $this->getFieldMappingOptions()['optional_fields'];
        
        foreach ($headers as $header) {
            $normalized = strtolower(str_replace([' ', '_', '-'], '', $header));
            
            foreach ($standardFields as $field => $label) {
                $normalizedField = strtolower(str_replace([' ', '_', '-'], '', $field));
                if (strpos($normalizedField, $normalized) !== false || strpos($normalized, $normalizedField) !== false) {
                    $suggestions[$header] = $field;
                    break;
                }
            }
        }
        
        return $suggestions;
    }

    protected function logImport(string $importId, Admin $user, string $filename, array $results): void
    {
        JobImportLog::create([
            'import_id' => $importId,
            'user_id' => $user->id,
            'filename' => $filename,
            'total_rows' => $results['total'],
            'imported_rows' => $results['imported'],
            'skipped_rows' => $results['skipped'],
            'failed_rows' => $results['failed'],
            'errors' => json_encode($results['errors']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}