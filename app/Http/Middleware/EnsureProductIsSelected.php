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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Example logic: check if a product is selected (you may have different criteria)
        if (!$request->session()->has('product_id')) {
            // Redirect user to the products page if no product is selected
            return redirect()->route('front.products')->with('error', 'Please select a product before proceeding.');
        }

        return $next($request);
    }
}
