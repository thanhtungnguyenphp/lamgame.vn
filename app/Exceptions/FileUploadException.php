<?php

namespace App\Exceptions;

use Exception;

class FileUploadException extends Exception
{
    /**
     * Create a new file upload exception instance.
     */
    public function __construct(string $message = 'File upload failed', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): bool
    {
        // Log the exception but don't report to external services
        // as this is typically user error, not system error
        return false;
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'error' => 'FILE_UPLOAD_ERROR'
        ], 400);
    }
}