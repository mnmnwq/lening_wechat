<?php

namespace App\Http\Middleware;

use Closure;

class Update
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
        //业务逻辑
        //9点到17点可以访问
        $start = strtotime('9:00:00');  //每天9点
        $end = strtotime("17:00:00");   //每天17点
        $_now_ = time();   //现在的时间
        if($_now_ >= $start && $_now_ <= $end){
            //可以通过
        }else{
            //不可以通过
            dd('当前时间不可访问');
        }
        return $next($request);
    }
}
