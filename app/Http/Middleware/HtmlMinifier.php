<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HtmlMinifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (
            $response instanceof \Illuminate\Http\Response &&
            strpos($response->headers->get('Content-Type'), 'text/html') !== false
        ) {
            $output = $response->getContent();

            // Minify: remove whitespace, tabs, new lines
            $output = preg_replace('/>\s+</', '><', $output); // collapse between tags
            $output = preg_replace('/\s+/', ' ', $output); // collapse spaces

            $response->setContent($output);
        }

        return $response;
    }
}
