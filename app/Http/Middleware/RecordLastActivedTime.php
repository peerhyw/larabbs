<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RecordLastActivedTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    /*
     * Laravel 的中间件从执行时机上分『前置中间件』和『后置中间件』，前置中间件是应用初始化完成以后立刻执行，此时控制器路由还未分配、控制器还未执行、视图还未渲染。后置中间件是即将离开应用的响应，此时控制器已将渲染好的视图返回，我们可以在后置中间件里修改响应。
     * return $next($request);前的为前置 后的为后置
     */
    public function handle($request, Closure $next)
    {
        //如果是登录用户的话
        if(Auth::check()){
            //记录最后登录时间
            Auth::user()->recordLastActivedAt();
        }

        return $next($request);
    }
}
