<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InputSanitizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize input data
        $this->sanitizeInput($request);

        return $next($request);
    }

    /**
     * Sanitize input data to prevent XSS and other attacks.
     */
    protected function sanitizeInput(Request $request): void
    {
        $input = $request->all();
        
        // Fields that should not be sanitized (like passwords)
        $skipFields = ['password', 'password_confirmation', '_token', '_method'];
        
        $sanitized = $this->sanitizeArray($input, $skipFields);
        
        $request->replace($sanitized);
    }

    /**
     * Recursively sanitize array data.
     */
    protected function sanitizeArray(array $data, array $skipFields = []): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $skipFields)) {
                $sanitized[$key] = $value;
                continue;
            }
            
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value, $skipFields);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    /**
     * Sanitize string input.
     */
    protected function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Trim whitespace
        $value = trim($value);
        
        // Remove potentially dangerous HTML tags while preserving safe ones
        $allowedTags = '<p><br><strong><em><u><ol><ul><li><a><h1><h2><h3><h4><h5><h6>';
        $value = strip_tags($value, $allowedTags);
        
        // Convert special characters to HTML entities
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        
        return $value;
    }
}