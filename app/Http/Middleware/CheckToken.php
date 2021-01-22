<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\generalTrait;
class CheckToken
{
    use generalTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth('api')->check()){
            return $next($request);
        }else{
            return $this->returnError(401 , "Unauthorized");
        }
    }
}
