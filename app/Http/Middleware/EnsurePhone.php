<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePhone
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $user->isAdmin() && ! $user->phone) {
            return redirect()->route('phone.show');
        }

        return $next($request);
    }
}