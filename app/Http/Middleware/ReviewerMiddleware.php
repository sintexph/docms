<?php

namespace App\Http\Middleware;

use Closure;

class ReviewerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=auth()->user();
        if($user==null)
            return redirect()->route('login');
            
        abort_if($user->perm_reviewer==false,403,"You have no permission to access the page");
        

        return $next($request);
    }
}
