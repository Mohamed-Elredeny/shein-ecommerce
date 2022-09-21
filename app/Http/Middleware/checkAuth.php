<?php

namespace App\Http\Middleware;

use App\Http\Traits\GeneralTrait;
use App\Models\Brand;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class checkAuth
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('token');
        $exist = User::where('remember_token', $token)->get();

        if (count($exist) > 0) {
            $this->user = $exist[0];
            return $next($request);
        } else {
            return $this->returnError(200, 'unauthenticated user');
        }
    }
}
