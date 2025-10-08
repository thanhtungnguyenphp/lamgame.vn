<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JobImportRequest;
use App\Services\JobService;
use App\Services\JobImportExportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class JobImportExportController extends Controller
{
    protected JobService $jobService;
    protected JobImportExportService $importExportService;

    public function __construct(
        JobService $jobService,
        JobImportExportService $importExportService
    ) {
        $this->jobService = $jobService;
        $this->importExportService = $importExportService;
    }

    /**
     * Import jobs from CSV/Excel file
     * 
     * @param JobImportRequest $request
     * @return JsonResponse
     */
    public function import(JobImportRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $file = $request->file('file');
            $mapping = $request->input('mapping', []);
            $skipDuplicates = $request->boolean('skip_duplicates', true);
            $validateOnly = $request->boolean('validate_only', false);

            // Validate file type
            $allowedMimes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type. Only CSV and Excel files are allowed.',
                ], 422);
            }

            // Process import
            $result = $this->importExportService->importJobs(
                $file,
                $user,
                $mapping,
                $skipDuplicates,
                $validateOnly
            );

            // Return validation results if validate_only is true
            if ($validateOnly) {
                return response()->json([
                    'success' => true,
                    'message' => 'File validation completed',
                    'data' => [
                        'validation_results' => $result,
                        'total_rows' => $result['total_rows'],
                        'valid_rows' => $result['valid_rows'],
                        'invalid_rows' => $result['invalid_rows'],
                        'errors' => $result['errors']
                    ]
                ]);
            }

            // Log import activity
            Log::info('Jobs imported', [
                'user_id' => $user->id,
                'imported_count' => $result['imported'],
                'skipped_count' => $result['skipped'],
                'failed_count' => $result['failed']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jobs imported successfully',
                'data' => [
                    'imported' => $result['imported'],
                    'skipped' => $result['skipped'],
                    'failed' => $result['failed'],
                    'total' => $result['total'],
                    'errors' => $result['errors'],
                    'import_id' => $result['import_id']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Job import failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export jobs to CSV/Excel/PDF
     * 
     * @param Request $request
     * @return JsonResponse|BinaryFileResponse
     */
    public function export(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'format' => 'required|in:csv,excel,pdf',
                'job_ids' => 'array',
                'job_ids.*' => 'integer|exists:products,id',
                'filters' => 'array',
                'include_applications' => 'boolean',
                'include_statistics' => 'boolean',
                'date_from' => 'date',
                'date_to' => 'date|after_or_equal:date_from',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $format = $request->input('format', 'csv');
            $jobIds = $request->input('job_ids');
            $filters = $request->input('filters', []);
            $includeApplications = $request->boolean('include_applications', false);
            $includeStatistics = $request->boolean('include_statistics', false);
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');

            // Get jobs to export
            if ($jobIds) {
                $jobs = $this->jobService->getUserJobsByIds($user, $jobIds);
            } else {
                $jobs = $this->jobService->getUserJobsForExport($user, $filters, $dateFrom, $dateTo);
            }

            if ($jobs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No jobs found for export'
                ], 404);
            }

            // Generate export file
            $exportResult = $this->importExportService->exportJobs(
                $jobs,
                $format,
                $includeApplications,
                $includeStatistics
            );

            // Log export activity
            Log::info('Jobs exported', [
                'user_id' => $user->id,
                'format' => $format,
                'job_count' => $jobs->count(),
                'include_applications' => $includeApplications,
                'include_statistics' => $includeStatistics
            ]);

            // Return file download
            if ($request->wantsJson() && $format === 'csv') {
                // Return CSV content as JSON for API consumers
                return response()->json([
                    'success' => true,
                    'message' => 'Export completed',
                    'data' => [
                        'format' => $format,
                        'job_count' => $jobs->count(),
                        'download_url' => $exportResult['download_url'],
                        'expires_at' => $exportResult['expires_at']
                    ]
                ]);
            }

            // Return file for direct download
            return response()->download(
                $exportResult['file_path'],
                $exportResult['filename'],
                $exportResult['headers']
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Job export failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download import template file
     * 
     * @param Request $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function downloadTemplate(Request $request)
    {
        try {
            $format = $request->input('format', 'csv');
            $includeExamples = $request->boolean('include_examples', true);

            if (!in_array($format, ['csv', 'excel'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid format. Only CSV and Excel templates are available.'
                ], 422);
            }

            // Generate template file
            $templateResult = $this->importExportService->generateImportTemplate($format, $includeExamples);

            // Log template download
            Log::info('Import template downloaded', [
                'user_id' => auth()->id(),
                'format' => $format,
                'include_examples' => $includeExamples
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Template generated',
                    'data' => [
                        'format' => $format,
                        'download_url' => $templateResult['download_url'],
                        'filename' => $templateResult['filename'],
                        'expires_at' => $templateResult['expires_at']
                    ]
                ]);
            }

            // Return file for direct download
            return response()->download(
                $templateResult['file_path'],
                $templateResult['filename'],
                $templateResult['headers']
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Template generation failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Template generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get import history for the authenticated user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getImportHistory(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $page = $request->input('page', 1);
            $perPage = min($request->input('per_page', 15), 50);

            $history = $this->importExportService->getImportHistory($user, $page, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Import history retrieved successfully',
                'data' => $history
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get import history', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve import history'
            ], 500);
        }
    }

    /**
     * Get field mapping options for import
     * 
     * @return JsonResponse
     */
    public function getFieldMappingOptions(): JsonResponse
    {
        try {
            $mappingOptions = $this->importExportService->getFieldMappingOptions();

            return response()->json([
                'success' => true,
                'message' => 'Field mapping options retrieved successfully',
                'data' => $mappingOptions
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get field mapping options', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve field mapping options'
            ], 500);
        }
    }

    /**
     * Preview import data before actual import
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function previewImport(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB max
                'mapping' => 'array',
                'rows_to_preview' => 'integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $mapping = $request->input('mapping', []);
            $rowsToPreview = $request->input('rows_to_preview', 10);

            $preview = $this->importExportService->previewImportData($file, $mapping, $rowsToPreview);

            return response()->json([
                'success' => true,
                'message' => 'Import preview generated successfully',
                'data' => $preview
            ]);

        } catch (\Exception $e) {
            Log::error('Import preview failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Preview failed: ' . $e->getMessage()
            ], 500);
        }
    }
}