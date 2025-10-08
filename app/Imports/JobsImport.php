<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class JobsImport implements ToCollection, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsEmptyRows, SkipsErrors, SkipsFailures
{
    protected array $mapping;
    protected ?int $limit;
    protected array $importedData = [];
    protected array $errors = [];
    protected array $failures = [];

    public function __construct(array $mapping = [], ?int $limit = null)
    {
        $this->mapping = $mapping;
        $this->limit = $limit;
    }

    /**
     * Process the imported collection
     * 
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection): void
    {
        $processed = 0;

        foreach ($collection as $row) {
            if ($this->limit && $processed >= $this->limit) {
                break;
            }

            // Apply field mapping
            $mappedRow = $this->applyMapping($row->toArray());
            
            // Clean and validate data
            $cleanedRow = $this->cleanRowData($mappedRow);
            
            // Only add non-empty rows
            if (!$this->isEmptyRow($cleanedRow)) {
                $this->importedData[] = $cleanedRow;
                $processed++;
            }
        }
    }

    /**
     * Define validation rules
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.description' => 'required|string',
            '*.short_description' => 'nullable|string|max:500',
            '*.location' => 'nullable|string|max:255',
            '*.salary_min' => 'nullable|numeric|min:0',
            '*.salary_max' => 'nullable|numeric|min:0|gte:*.salary_min',
            '*.employment_type' => 'nullable|string|in:full-time,part-time,contract,internship,freelance',
            '*.experience_level' => 'nullable|string|in:entry,mid,senior,lead,executive',
            '*.application_deadline' => 'nullable|date|after:today',
            '*.status' => 'nullable|string',
            '*.is_featured' => 'nullable|boolean',
            '*.contact_email' => 'nullable|email|max:255',
        ];
    }

    /**
     * Custom validation messages
     * 
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'Job title is required',
            '*.name.max' => 'Job title cannot exceed 255 characters',
            '*.description.required' => 'Job description is required',
            '*.short_description.max' => 'Short description cannot exceed 500 characters',
            '*.salary_min.numeric' => 'Minimum salary must be a number',
            '*.salary_min.min' => 'Minimum salary cannot be negative',
            '*.salary_max.numeric' => 'Maximum salary must be a number',
            '*.salary_max.gte' => 'Maximum salary must be greater than or equal to minimum salary',
            '*.employment_type.in' => 'Employment type must be one of: full-time, part-time, contract, internship, freelance',
            '*.experience_level.in' => 'Experience level must be one of: entry, mid, senior, lead, executive',
            '*.application_deadline.date' => 'Application deadline must be a valid date',
            '*.application_deadline.after' => 'Application deadline must be in the future',
            '*.contact_email.email' => 'Contact email must be a valid email address',
        ];
    }

    /**
     * Get batch size for chunk processing
     * 
     * @return int
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Get chunk size for reading
     * 
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Handle import errors
     * 
     * @param Throwable $e
     * @return void
     */
    public function onError(Throwable $e): void
    {
        $this->errors[] = [
            'message' => $e->getMessage(),
            'line' => $e->getLine() ?? 'Unknown',
            'file' => $e->getFile() ?? 'Unknown'
        ];
    }

    /**
     * Handle validation failures
     * 
     * @param Failure ...$failures
     * @return void
     */
    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ];
        }
    }

    /**
     * Apply field mapping to row data
     * 
     * @param array $row
     * @return array
     */
    protected function applyMapping(array $row): array
    {
        if (empty($this->mapping)) {
            return $row;
        }

        $mappedRow = [];
        foreach ($this->mapping as $sourceField => $targetField) {
            // Handle both numeric and string keys
            $sourceValue = $row[$sourceField] ?? $row[strtolower($sourceField)] ?? null;
            if ($sourceValue !== null) {
                $mappedRow[$targetField] = $sourceValue;
            }
        }

        // Include unmapped fields that might be standard
        $standardFields = [
            'name', 'description', 'short_description', 'location',
            'salary_min', 'salary_max', 'employment_type', 'experience_level',
            'skills', 'requirements', 'benefits', 'application_deadline',
            'status', 'is_featured', 'category', 'company_name', 'contact_email'
        ];

        foreach ($standardFields as $field) {
            if (!isset($mappedRow[$field]) && isset($row[$field])) {
                $mappedRow[$field] = $row[$field];
            }
        }

        return $mappedRow;
    }

    /**
     * Clean and standardize row data
     * 
     * @param array $row
     * @return array
     */
    protected function cleanRowData(array $row): array
    {
        $cleanedRow = [];

        foreach ($row as $key => $value) {
            // Convert value to string and trim
            $cleanedValue = is_string($value) ? trim($value) : $value;
            
            // Skip empty values
            if ($cleanedValue === '' || $cleanedValue === null) {
                continue;
            }

            // Apply field-specific cleaning
            switch ($key) {
                case 'name':
                case 'short_description':
                case 'location':
                case 'category':
                case 'company_name':
                    $cleanedRow[$key] = $this->cleanTextValue($cleanedValue);
                    break;

                case 'description':
                case 'requirements':
                case 'benefits':
                    $cleanedRow[$key] = $this->cleanHtmlValue($cleanedValue);
                    break;

                case 'salary_min':
                case 'salary_max':
                    $cleanedRow[$key] = $this->cleanNumericValue($cleanedValue);
                    break;

                case 'employment_type':
                    $cleanedRow[$key] = $this->cleanEmploymentType($cleanedValue);
                    break;

                case 'experience_level':
                    $cleanedRow[$key] = $this->cleanExperienceLevel($cleanedValue);
                    break;

                case 'application_deadline':
                    $cleanedRow[$key] = $this->cleanDateValue($cleanedValue);
                    break;

                case 'status':
                    $cleanedRow[$key] = $this->cleanBooleanValue($cleanedValue) ? 'active' : 'inactive';
                    break;

                case 'is_featured':
                    $cleanedRow[$key] = $this->cleanBooleanValue($cleanedValue);
                    break;

                case 'contact_email':
                    $email = $this->cleanEmailValue($cleanedValue);
                    if ($email) {
                        $cleanedRow[$key] = $email;
                    }
                    break;

                case 'skills':
                    $cleanedRow[$key] = $this->cleanSkillsValue($cleanedValue);
                    break;

                default:
                    $cleanedRow[$key] = $cleanedValue;
            }
        }

        return $cleanedRow;
    }

    /**
     * Check if row is empty
     * 
     * @param array $row
     * @return bool
     */
    protected function isEmptyRow(array $row): bool
    {
        // Row is empty if it has no name or description
        return empty($row['name']) && empty($row['description']);
    }

    /**
     * Clean text value
     * 
     * @param mixed $value
     * @return string
     */
    protected function cleanTextValue($value): string
    {
        $cleaned = is_string($value) ? $value : (string) $value;
        $cleaned = trim($cleaned);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned); // Replace multiple spaces
        return $cleaned;
    }

    /**
     * Clean HTML content
     * 
     * @param mixed $value
     * @return string
     */
    protected function cleanHtmlValue($value): string
    {
        $cleaned = is_string($value) ? $value : (string) $value;
        $cleaned = trim($cleaned);
        
        // If it looks like plain text, return as is
        if (!preg_match('/<[^>]+>/', $cleaned)) {
            return $cleaned;
        }

        // Clean HTML but preserve basic structure
        $cleaned = strip_tags($cleaned, '<p><br><ul><ol><li><strong><b><em><i>');
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
    }

    /**
     * Clean numeric value
     * 
     * @param mixed $value
     * @return float|null
     */
    protected function cleanNumericValue($value): ?float
    {
        if (empty($value)) {
            return null;
        }

        // Remove non-numeric characters except dots and commas
        $cleaned = preg_replace('/[^\d.,]/', '', (string) $value);
        
        // Handle different decimal separators
        $cleaned = str_replace(',', '.', $cleaned);
        
        // Check if it's a valid number
        if (is_numeric($cleaned)) {
            return (float) $cleaned;
        }

        return null;
    }

    /**
     * Clean employment type
     * 
     * @param mixed $value
     * @return string
     */
    protected function cleanEmploymentType($value): string
    {
        $cleaned = strtolower(trim((string) $value));
        
        $mappings = [
            'full-time' => 'full-time',
            'fulltime' => 'full-time',
            'full time' => 'full-time',
            'part-time' => 'part-time',
            'parttime' => 'part-time',
            'part time' => 'part-time',
            'contract' => 'contract',
            'contractor' => 'contract',
            'internship' => 'internship',
            'intern' => 'internship',
            'freelance' => 'freelance',
            'freelancer' => 'freelance'
        ];

        return $mappings[$cleaned] ?? $cleaned;
    }

    /**
     * Clean experience level
     * 
     * @param mixed $value
     * @return string
     */
    protected function cleanExperienceLevel($value): string
    {
        $cleaned = strtolower(trim((string) $value));
        
        $mappings = [
            'entry' => 'entry',
            'entry-level' => 'entry',
            'junior' => 'entry',
            'mid' => 'mid',
            'mid-level' => 'mid',
            'middle' => 'mid',
            'senior' => 'senior',
            'senior-level' => 'senior',
            'lead' => 'lead',
            'team lead' => 'lead',
            'executive' => 'executive',
            'manager' => 'executive'
        ];

        return $mappings[$cleaned] ?? $cleaned;
    }

    /**
     * Clean date value
     * 
     * @param mixed $value
     * @return string|null
     */
    protected function cleanDateValue($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Clean boolean value
     * 
     * @param mixed $value
     * @return bool
     */
    protected function cleanBooleanValue($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $trueValues = ['yes', 'true', '1', 'active', 'enabled', 'on', 'featured'];
        return in_array(strtolower(trim((string) $value)), $trueValues);
    }

    /**
     * Clean email value
     * 
     * @param mixed $value
     * @return string|null
     */
    protected function cleanEmailValue($value): ?string
    {
        $cleaned = trim((string) $value);
        
        if (filter_var($cleaned, FILTER_VALIDATE_EMAIL)) {
            return strtolower($cleaned);
        }

        return null;
    }

    /**
     * Clean skills value
     * 
     * @param mixed $value
     * @return string
     */
    protected function cleanSkillsValue($value): string
    {
        $cleaned = trim((string) $value);
        
        // Split by common separators and clean each skill
        $skills = preg_split('/[,;|]/', $cleaned);
        $cleanedSkills = [];
        
        foreach ($skills as $skill) {
            $skill = trim($skill);
            if (!empty($skill)) {
                $cleanedSkills[] = $skill;
            }
        }
        
        return implode(', ', $cleanedSkills);
    }

    /**
     * Get imported data
     * 
     * @return array
     */
    public function getImportedData(): array
    {
        return $this->importedData;
    }

    /**
     * Get import errors
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get validation failures
     * 
     * @return array
     */
    public function getFailures(): array
    {
        return $this->failures;
    }
}