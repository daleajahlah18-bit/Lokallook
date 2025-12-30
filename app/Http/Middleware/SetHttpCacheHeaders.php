<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetHttpCacheHeaders extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jangan cache authenticated requests
        if ($request->user() !== null) {
            return $response->withoutCaching();
        }

        // Jangan cache POST requests
        if ($request->isMethod('POST')) {
            return $response->withoutCaching();
        }

        // Setup cache headers untuk GET requests
        if ($request->isMethod('GET')) {
            // Set ETag
            $etag = hash('sha256', $response->getContent());
            $response->setEtag($etag);

            // Set Last-Modified header
            $response->setLastModified(now());

            // Check If-None-Match (ETag)
            if (in_array($request->getETags(), [$etag], true)) {
                return response()->setNotModified();
            }

            // Tentukan cache duration berdasarkan route
            $cacheControl = $this->getCacheControl($request);
            $response->headers->set('Cache-Control', $cacheControl);

            // Set Vary header untuk content negotiation
            $response->headers->set('Vary', 'Accept-Encoding');

            // Enable public caching
            $response->setPublic();
        }

        return $response;
    }

    /**
     * Determine cache control header berdasarkan route
     *
     * @param Request $request
     * @return string
     */
    private function getCacheControl(Request $request): string
    {
        $routeName = $request->route()?->getName() ?? '';
        $pathInfo = $request->getPathInfo();

        // Static assets - cache for 1 year
        if (preg_match('/\.(js|css|woff2?|ttf|otf|eot|jpg|jpeg|png|gif|svg|webp)$/', $pathInfo)) {
            return 'public, max-age=31536000, immutable';
        }

        // Product pages - cache for 1 day
        if (str_contains($routeName, 'product')) {
            return 'public, max-age=86400, stale-while-revalidate=604800';
        }

        // Category pages - cache for 1 day
        if (str_contains($routeName, 'category')) {
            return 'public, max-age=86400, stale-while-revalidate=604800';
        }

        // Home page - cache for 1 hour
        if (str_contains($routeName, 'home')) {
            return 'public, max-age=3600, stale-while-revalidate=86400';
        }

        // Default - cache for 1 hour
        return 'public, max-age=3600, stale-while-revalidate=86400';
    }
}
