<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class role
{
    /**
     * Handle an incoming request.
     *  
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }
        $user = auth()->user();

        if ($user->role === User::ROLE_SUPERADMIN) {
            return $next($request);
        }

        $companyId = session('company_id');
        if (! $companyId) {
            return redirect()->route('home')->with('error', 'Please select a company from the navbar.');
        }

        $isCompanyAdmin = $user->companies()->where('company_id', $companyId)->wherePivot('role', 'admin')->exists();

        if ($isCompanyAdmin) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', "You don't have admin access for the selected company.");
    }
}
