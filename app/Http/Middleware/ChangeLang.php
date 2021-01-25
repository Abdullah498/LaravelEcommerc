<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\generalTrait;

class ChangeLang
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
        $lang = $request->header('lang');
        if($lang){
            if($lang == 'en' || $lang == 'ar'){
                app()->setLocale($lang);
                return $next($request);
            }else{
                return $this->returnError(400 , 'Language is not supported');
            }
        }else{
            return $this->returnError(400 , 'you must send language');
        }
    }
}
