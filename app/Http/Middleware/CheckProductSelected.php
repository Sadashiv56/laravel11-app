<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckProductSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  \Illuminate\Http\Response  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if 'bookingData' or 'product_id' is available in the session
        if (!Session::has('bookingData.product_id')) {
            // Redirect to home if product not selected
            return redirect()->route('front.home')->with('error', 'Please select a product first.');
        }

        // Allow request if product is selected
        return $next($request);
    }
}
