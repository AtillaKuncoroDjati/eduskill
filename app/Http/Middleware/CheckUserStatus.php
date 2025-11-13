<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->status === 'banned') {
            auth()->logout();
            session()->flash('failed_message', 'Akun Anda telah dibanned. Silakan hubungi administrator untuk informasi lebih lanjut.');
            return to_route('auth.view');
        }

        return $next($request);
    }
}
