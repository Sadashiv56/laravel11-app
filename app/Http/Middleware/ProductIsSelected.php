<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProductIsSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a product is selected (exists in session)
        if (!$request->session()->has('product_id')) {
            // Redirect to the product selection page with a message
            return redirect()->route('front.products')->with('error', 'Please select a product before proceeding.');
        }

        return $next($request);
    }
}
