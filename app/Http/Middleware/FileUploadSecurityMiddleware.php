<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class FileUploadSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check all uploaded files for security
        $this->validateUploadedFiles($request);

        return $next($request);
    }

    /**
     * Validate all uploaded files for security.
     */
    protected function validateUploadedFiles(Request $request): void
    {
        $files = $request->allFiles();
        
        foreach ($files as $key => $file) {
            if (is_array($file)) {
                foreach ($file as $uploadedFile) {
                    if ($uploadedFile instanceof UploadedFile) {
                        $this->validateFile($uploadedFile);
                    }
                }
            } elseif ($file instanceof UploadedFile) {
                $this->validateFile($file);
            }
        }
    }

    /**
     * Validate individual uploaded file.
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            abort(413, 'File too large. Maximum size is 10MB.');
        }

        // Check for dangerous file extensions
        $dangerousExtensions = [
            'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'com', 'pif', 'scr',
            'vbs', 'js', 'jar', 'sh', 'py', 'pl', 'cgi', 'asp', 'aspx', 'jsp', 'cfm'
        ];

        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, $dangerousExtensions)) {
            abort(422, 'File type not allowed for security reasons.');
        }

        // Check MIME type for images
        if ($this->isImageUpload($file)) {
            $this->validateImageFile($file);
        }

        // Check for embedded PHP code in files
        $this->scanForMaliciousContent($file);
    }

    /**
     * Check if the uploaded file is supposed to be an image.
     */
    protected function isImageUpload(UploadedFile $file): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $imageExtensions);
    }

    /**
     * Validate image files specifically.
     */
    protected function validateImageFile(UploadedFile $file): void
    {
        // Verify MIME type matches extension
        $allowedMimeTypes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 
            'image/webp', 'image/bmp', 'image/svg+xml'
        ];

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            abort(422, 'Invalid image file type.');
        }

        // For non-SVG images, verify it's actually an image
        if ($file->getMimeType() !== 'image/svg+xml') {
            $imageInfo = @getimagesize($file->getPathname());
            if ($imageInfo === false) {
                abort(422, 'Invalid image file.');
            }
        }
    }

    /**
     * Scan file content for malicious code.
     */
    protected function scanForMaliciousContent(UploadedFile $file): void
    {
        // Read first 1KB of file to check for malicious patterns
        $handle = fopen($file->getPathname(), 'r');
        if ($handle) {
            $content = fread($handle, 1024);
            fclose($handle);

            // Check for PHP tags and other suspicious patterns
            $maliciousPatterns = [
                '/<\?php/i',
                '/<\?=/i',
                '/<script/i',
                '/eval\s*\(/i',
                '/exec\s*\(/i',
                '/system\s*\(/i',
                '/shell_exec\s*\(/i',
                '/passthru\s*\(/i',
                '/base64_decode\s*\(/i',
            ];

            foreach ($maliciousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    \Log::warning('Malicious file upload attempt detected', [
                        'filename' => $file->getClientOriginalName(),
                        'ip' => request()->ip(),
                        'user_id' => auth()->id(),
                        'pattern' => $pattern,
                    ]);
                    
                    abort(422, 'File contains potentially malicious content.');
                }
            }
        }
    }
}