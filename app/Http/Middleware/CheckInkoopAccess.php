<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckInkoopAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Niet ingelogd');
        }

        $debugInfo = [];

        // Check 1: Purchase department (case insensitive)
        if ($user->department) {
            $deptName = $user->department->name;
            $debugInfo['department_check'] = $deptName;

            if (strtolower($deptName) === 'purchase') {
                Log::info('Purchase access granted', $debugInfo);
                return $next($request);
            }
        }

        // Check 2: Manager role
        if ($user->role === 'manager') {
            Log::info('Purchase access granted via manager role');
            return $next($request);
        }

        // Check 3: Admin role
        if ($user->role === 'admin') {
            Log::info('Purchase access granted via admin role');
            return $next($request);
        }

        // Access Denied
        $debugInfo['access_granted'] = 'DENIED';
        Log::warning('Purchase access denied', $debugInfo);
        abort(403, 'Alleen inkoop medewerkers (Purchase department), managers en admins hebben toegang.');
    }
}
