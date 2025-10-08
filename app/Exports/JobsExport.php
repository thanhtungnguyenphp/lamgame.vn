<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JobsExport implements FromArray, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    protected array $data;
    protected bool $includeStatistics;
    protected string $title;

    public function __construct(array $data, bool $includeStatistics = false, string $title = 'Jobs Export')
    {
        $this->data = $data;
        $this->includeStatistics = $includeStatistics;
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * Define the headings for the export
     * 
     * @return array
     */
    public function headings(): array
    {
        $baseHeadings = [
            'ID',
            'Job Title',
            'Description',
            'Short Description', 
            'Location',
            'Min Salary',
            'Max Salary',
            'Status',
            'Created Date',
            'Updated Date'
        ];

        if ($this->includeStatistics) {
            $baseHeadings = array_merge($baseHeadings, [
                'Views',
                'Applications',
                'Conversion Rate (%)'
            ]);
        }

        return $baseHeadings;
    }

    /**
     * Map each row of data
     * 
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $baseData = [
            $row['id'] ?? '',
            $row['name'] ?? '',
            $this->cleanDescription($row['description'] ?? ''),
            $row['short_description'] ?? '',
            $row['location'] ?? '',
            $this->formatSalary($row['salary_min'] ?? null),
            $this->formatSalary($row['salary_max'] ?? null),
            $row['status'] ?? '',
            $this->formatDate($row['created_at'] ?? ''),
            $this->formatDate($row['updated_at'] ?? '')
        ];

        if ($this->includeStatistics) {
            $baseData = array_merge($baseData, [
                $row['views'] ?? 0,
                $row['applications'] ?? 0,
                $this->formatPercentage($row['conversion_rate'] ?? 0)
            ]);
        }

        return $baseData;
    }

    /**
     * Apply styles to the worksheet
     * 
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        $lastColumn = $this->includeStatistics ? 'M' : 'J';
        $lastRow = count($this->data) + 1;

        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            
            // Data rows styling
            "A2:{$lastColumn}{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],

            // Alternate row colors
            "A2:{$lastColumn}{$lastRow}" => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FA'],
                ],
            ],
        ];
    }

    /**
     * Define column widths
     * 
     * @return array
     */
    public function columnWidths(): array
    {
        $baseWidths = [
            'A' => 8,   // ID
            'B' => 25,  // Job Title
            'C' => 40,  // Description
            'D' => 30,  // Short Description
            'E' => 20,  // Location
            'F' => 12,  // Min Salary
            'G' => 12,  // Max Salary
            'H' => 10,  // Status
            'I' => 15,  // Created Date
            'J' => 15,  // Updated Date
        ];

        if ($this->includeStatistics) {
            $baseWidths = array_merge($baseWidths, [
                'K' => 10, // Views
                'L' => 12, // Applications
                'M' => 15, // Conversion Rate
            ]);
        }

        return $baseWidths;
    }

    /**
     * Set the worksheet title
     * 
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Clean HTML tags from description
     * 
     * @param string $description
     * @return string
     */
    protected function cleanDescription(string $description): string
    {
        // Remove HTML tags
        $cleaned = strip_tags($description);
        
        // Decode HTML entities
        $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Replace multiple whitespaces with single space
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        // Trim whitespace
        $cleaned = trim($cleaned);
        
        // Limit length for Excel compatibility (Excel has a 32767 character limit per cell)
        if (strlen($cleaned) > 1000) {
            $cleaned = substr($cleaned, 0, 997) . '...';
        }
        
        return $cleaned;
    }

    /**
     * Format salary value
     * 
     * @param mixed $salary
     * @return string
     */
    protected function formatSalary($salary): string
    {
        if (empty($salary) || !is_numeric($salary)) {
            return 'N/A';
        }

        return number_format((float) $salary, 0);
    }

    /**
     * Format date for display
     * 
     * @param mixed $date
     * @return string
     */
    protected function formatDate($date): string
    {
        if (empty($date)) {
            return 'N/A';
        }

        try {
            if (is_string($date)) {
                $date = \Carbon\Carbon::parse($date);
            }
            
            return $date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return 'Invalid Date';
        }
    }

    /**
     * Format percentage value
     * 
     * @param mixed $percentage
     * @return string
     */
    protected function formatPercentage($percentage): string
    {
        if (!is_numeric($percentage)) {
            return '0%';
        }

        return number_format((float) $percentage, 2) . '%';
    }

    /**
     * Get export summary information
     * 
     * @return array
     */
    public function getExportSummary(): array
    {
        $totalJobs = count($this->data);
        $activeJobs = 0;
        $inactiveJobs = 0;
        $totalSalaryMin = 0;
        $totalSalaryMax = 0;
        $jobsWithSalary = 0;

        foreach ($this->data as $job) {
            if (isset($job['status'])) {
                if ($job['status'] === 'Active' || $job['status'] === 1) {
                    $activeJobs++;
                } else {
                    $inactiveJobs++;
                }
            }

            if (!empty($job['salary_min']) && is_numeric($job['salary_min'])) {
                $totalSalaryMin += (float) $job['salary_min'];
                $jobsWithSalary++;
            }

            if (!empty($job['salary_max']) && is_numeric($job['salary_max'])) {
                $totalSalaryMax += (float) $job['salary_max'];
            }
        }

        return [
            'total_jobs' => $totalJobs,
            'active_jobs' => $activeJobs,
            'inactive_jobs' => $inactiveJobs,
            'average_salary_min' => $jobsWithSalary > 0 ? round($totalSalaryMin / $jobsWithSalary, 2) : 0,
            'average_salary_max' => $jobsWithSalary > 0 ? round($totalSalaryMax / $jobsWithSalary, 2) : 0,
            'export_date' => now()->format('Y-m-d H:i:s'),
        ];
    }
}