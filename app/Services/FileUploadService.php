<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Exceptions\FileUploadException;
use Carbon\Carbon;

class FileUploadService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB in bytes

    /**
     * Upload CV file and return the storage path
     */
    public function uploadCV(UploadedFile $file): string
    {
        try {
            // Validate file
            $this->validateFile($file);
            
            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            
            // Determine storage path
            $storagePath = $this->getStoragePath();
            
            // Store the file
            $fullPath = $file->storeAs($storagePath, $filename, 'private');
            
            if (!$fullPath) {
                throw new FileUploadException('Failed to store file on disk');
            }
            
            // Verify file was actually saved
            if (!Storage::disk('private')->exists($fullPath)) {
                throw new FileUploadException('File was not saved properly');
            }
            
            Log::info('CV file uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $fullPath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
            
            return $fullPath;
            
        } catch (FileUploadException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('CV upload failed', [
                'original_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new FileUploadException('Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new FileUploadException('Invalid file upload');
        }

        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new FileUploadException('File size exceeds maximum limit of 5MB');
        }

        // Check file size is not zero
        if ($file->getSize() === 0) {
            throw new FileUploadException('File is empty');
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw new FileUploadException("Invalid file type. Only PDF, DOC, and DOCX files are allowed. Detected: {$mimeType}");
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new FileUploadException("Invalid file extension. Only PDF, DOC, and DOCX files are allowed. Detected: {$extension}");
        }

        // Additional security check: verify file contents match extension
        $this->verifyFileContents($file);
    }

    /**
     * Verify file contents match the expected type
     */
    private function verifyFileContents(UploadedFile $file): void
    {
        $handle = fopen($file->getPathname(), 'rb');
        if (!$handle) {
            throw new FileUploadException('Cannot read file contents');
        }

        // Read first few bytes to check file signature
        $header = fread($handle, 8);
        fclose($handle);

        $extension = strtolower($file->getClientOriginalExtension());

        // Check file signatures
        switch ($extension) {
            case 'pdf':
                if (strpos($header, '%PDF') !== 0) {
                    throw new FileUploadException('File does not appear to be a valid PDF');
                }
                break;
                
            case 'doc':
                // DOC files start with specific bytes
                if (substr($header, 0, 4) !== "\xD0\xCF\x11\xE0") {
                    throw new FileUploadException('File does not appear to be a valid DOC document');
                }
                break;
                
            case 'docx':
                // DOCX files are ZIP archives, check ZIP signature
                if (substr($header, 0, 2) !== 'PK') {
                    throw new FileUploadException('File does not appear to be a valid DOCX document');
                }
                break;
        }
    }

    /**
     * Generate unique filename for the uploaded file
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Clean the original filename
        $cleanName = $this->sanitizeFilename($originalName);
        
        // Limit length
        $cleanName = Str::limit($cleanName, 50, '');
        
        // Generate unique identifier
        $uniqueId = uniqid();
        $timestamp = Carbon::now()->format('Ymd_His');
        
        return "{$cleanName}_{$timestamp}_{$uniqueId}.{$extension}";
    }

    /**
     * Sanitize filename to remove dangerous characters
     */
    private function sanitizeFilename(string $filename): string
    {
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        
        // Remove consecutive underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Remove leading/trailing underscores
        return trim($filename, '_');
    }

    /**
     * Get storage path for CV files
     */
    private function getStoragePath(): string
    {
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        
        return "cvs/{$year}/{$month}";
    }

    /**
     * Delete CV file
     */
    public function deleteCV(string $filePath): bool
    {
        try {
            if (Storage::disk('private')->exists($filePath)) {
                $deleted = Storage::disk('private')->delete($filePath);
                
                Log::info('CV file deleted', [
                    'file_path' => $filePath,
                    'deleted' => $deleted
                ]);
                
                return $deleted;
            }
            
            return true; // File doesn't exist, consider it deleted
            
        } catch (\Exception $e) {
            Log::error('Failed to delete CV file', [
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get download URL for CV file
     */
    public function getCVDownloadUrl(string $filePath): string
    {
        // Generate temporary signed URL for secure download
        return Storage::disk('private')->temporaryUrl(
            $filePath,
            Carbon::now()->addHours(1) // URL expires in 1 hour
        );
    }

    /**
     * Check if CV file exists
     */
    public function cvExists(string $filePath): bool
    {
        return Storage::disk('private')->exists($filePath);
    }

    /**
     * Get CV file information
     */
    public function getCVInfo(string $filePath): ?array
    {
        try {
            if (!$this->cvExists($filePath)) {
                return null;
            }
            
            $size = Storage::disk('private')->size($filePath);
            $lastModified = Storage::disk('private')->lastModified($filePath);
            $filename = basename($filePath);
            
            return [
                'filename' => $filename,
                'path' => $filePath,
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'last_modified' => Carbon::createFromTimestamp($lastModified),
                'extension' => pathinfo($filename, PATHINFO_EXTENSION),
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to get CV file info', [
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Cleanup old CV files (older than specified days)
     */
    public function cleanupOldCVs(int $daysOld = 90): int
    {
        try {
            $cutoffDate = Carbon::now()->subDays($daysOld);
            $deletedCount = 0;
            
            // Get all CV files
            $files = Storage::disk('private')->allFiles('cvs');
            
            foreach ($files as $file) {
                $lastModified = Carbon::createFromTimestamp(
                    Storage::disk('private')->lastModified($file)
                );
                
                if ($lastModified->lt($cutoffDate)) {
                    // Check if file is still referenced in database
                    $isReferenced = \App\Models\JobApplication::where('resume_file_path', $file)
                        ->exists();
                    
                    if (!$isReferenced) {
                        Storage::disk('private')->delete($file);
                        $deletedCount++;
                    }
                }
            }
            
            Log::info('CV cleanup completed', [
                'days_old' => $daysOld,
                'deleted_count' => $deletedCount
            ]);
            
            return $deletedCount;
            
        } catch (\Exception $e) {
            Log::error('CV cleanup failed', [
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }
}