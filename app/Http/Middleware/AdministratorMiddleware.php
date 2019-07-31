<?php

namespace App\Http\Middleware;

use Closure;


class AdministratorMiddleware
{
    /**
     * Handle permission in managing the account
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
            
        abort_if($user->perm_administrator==false,403,"You have no permission to access the page");
        

        return $next($request);
    }
}
