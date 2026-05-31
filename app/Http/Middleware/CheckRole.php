<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // check session login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // check role user
        $user = Auth::user();
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // jika role tidak sesuai, tampilkan error 403 Forbidden
        abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
    }
}
