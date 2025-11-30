<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow access if user is not authenticated
        if (! $user) {
            return $next($request);
        }

        // Allow access to subscription-related routes
        if ($request->routeIs('subscription.*') || $request->routeIs('register')) {
            return $next($request);
        }

        // Check if user has an active subscription
        if (! $user->subscribed('default')) {
            return redirect()->route('subscription.checkout');
        }

        return $next($request);
    }
}
