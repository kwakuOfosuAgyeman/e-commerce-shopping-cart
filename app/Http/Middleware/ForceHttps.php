<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * SECURITY: Force HTTPS in production to prevent man-in-the-middle attacks.
     * This middleware redirects HTTP requests to HTTPS when enabled.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only enforce HTTPS in production when enabled
        if ($this->shouldForceHttps($request)) {
            // Redirect to HTTPS if request is not secure
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri(), 301);
            }

            // Set HTTPS for URL generation
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        return $next($request);
    }

    /**
     * Determine if HTTPS should be enforced.
     */
    protected function shouldForceHttps(Request $request): bool
    {
        // Check if force HTTPS is enabled via environment
        if (!config('app.force_https', false)) {
            return false;
        }

        // Don't force HTTPS in local environment
        if (app()->environment('local', 'testing')) {
            return false;
        }

        // Trust proxy headers for load balancers
        $request->setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );

        return true;
    }
}
