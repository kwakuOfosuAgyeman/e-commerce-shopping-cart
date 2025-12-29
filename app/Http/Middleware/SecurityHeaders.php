<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to add security headers to all responses.
 *
 * Protects against common web vulnerabilities including:
 * - Clickjacking (X-Frame-Options)
 * - XSS attacks (X-XSS-Protection, CSP)
 * - MIME type sniffing (X-Content-Type-Options)
 * - Protocol downgrade attacks (HSTS)
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add headers in non-local environments
        if (!app()->environment('local', 'testing')) {
            $this->addSecurityHeaders($response, $request);
        }

        return $response;
    }

    /**
     * Add security headers to the response.
     */
    protected function addSecurityHeaders(Response $response, Request $request): void
    {
        // Prevent clickjacking - page cannot be embedded in iframes
        $response->headers->set('X-Frame-Options', 'DENY');

        // Enable browser XSS filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer policy - send origin only for cross-origin requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy - restrict resource loading
        $response->headers->set('Content-Security-Policy', $this->buildContentSecurityPolicy());

        // HTTP Strict Transport Security (HSTS)
        // Force HTTPS for 1 year, include subdomains
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Permissions Policy - restrict browser features
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(self)'
        );

        // Prevent information disclosure
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
    }

    /**
     * Build Content Security Policy header value.
     */
    protected function buildContentSecurityPolicy(): string
    {
        $policies = [
            // Default to same origin
            "default-src 'self'",

            // Scripts - allow self and inline (needed for some Laravel features)
            "script-src 'self' 'unsafe-inline'",

            // Styles - allow self and inline (needed for dynamic styling)
            "style-src 'self' 'unsafe-inline'",

            // Images - allow self, data URIs, and HTTPS sources for external images
            "img-src 'self' data: https:",

            // Fonts - allow self and data URIs
            "font-src 'self' data:",

            // Connect - allow self and payment/SMS provider APIs
            "connect-src 'self' https://graph.facebook.com",

            // Prevent page from being embedded
            "frame-ancestors 'none'",

            // Restrict base URI to same origin
            "base-uri 'self'",

            // Restrict form actions to same origin
            "form-action 'self'",

            // Upgrade HTTP requests to HTTPS
            "upgrade-insecure-requests",
        ];

        return implode('; ', $policies);
    }
}
