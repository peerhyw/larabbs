<?php

namespace App\Http\Middleware;

use Closure;

class ChangeLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    //前置
    public function handle($request, Closure $next)
    {
        $language = $request->header('accept-language');
        if($language){
            \App::setLocale($language);
        }

        return $next($request);
    }
}
