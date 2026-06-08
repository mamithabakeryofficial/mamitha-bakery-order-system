<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DailyLimit;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckStoreOpen
{
    /**
     * Handle an incoming request.
     *
     * Blocks customer access to catalog, cart, and checkout when the store
     * is outside its configured operational hours.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow admins, kitchen staff, and couriers to bypass this check
        if ($user && in_array($user->role, ['admin', 'kitchen', 'courier'])) {
            return $next($request);
        }

        $dailyLimit = DailyLimit::first();

        // If no configuration exists or the limit system is inactive, allow access
        if (!$dailyLimit || !$dailyLimit->is_active) {
            return $next($request);
        }

        $now = Carbon::now();
        $openingTime = Carbon::createFromTimeString($dailyLimit->opening_time);
        $closingTime = Carbon::createFromTimeString($dailyLimit->closing_time);

        if ($now->lt($openingTime) || $now->gt($closingTime)) {
            return redirect()->route('customer.store_closed');
        }

        return $next($request);
    }
}
