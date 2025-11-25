<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LazyLoadingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process HTML responses
        if ($response instanceof \Illuminate\Http\Response && 
            str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            
            $content = $response->getContent();
            $optimizedContent = $this->addLazyLoadingToImages($content);
            $response->setContent($optimizedContent);
        }

        return $response;
    }

    /**
     * Add lazy loading attributes to images.
     */
    protected function addLazyLoadingToImages(string $content): string
    {
        // Add loading="lazy" to img tags that don't already have it
        $content = preg_replace_callback(
            '/<img\b[^>]*>/i',
            function ($matches) {
                $imgTag = $matches[0];
                
                // Skip if already has loading attribute
                if (preg_match('/loading\s*=\s*["\'][^"\']*["\']/i', $imgTag)) {
                    return $imgTag;
                }
                
                // Skip if it's above the fold (first few images)
                static $imageCount = 0;
                $imageCount++;
                
                if ($imageCount <= 3) {
                    return $imgTag;
                }
                
                // Add loading="lazy" before the closing >
                return str_replace('>', ' loading="lazy">', $imgTag);
            },
            $content
        );

        // Add decoding="async" for better performance
        $content = preg_replace_callback(
            '/<img\b[^>]*>/i',
            function ($matches) {
                $imgTag = $matches[0];
                
                // Skip if already has decoding attribute
                if (preg_match('/decoding\s*=\s*["\'][^"\']*["\']/i', $imgTag)) {
                    return $imgTag;
                }
                
                // Add decoding="async" before the closing >
                return str_replace('>', ' decoding="async">', $imgTag);
            },
            $content
        );

        return $content;
    }
}