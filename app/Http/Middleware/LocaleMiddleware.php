<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Session;
use App;
class LocaleMiddleware
{
    public static $mainLanguage = 'en'; //основной язык
    public static $languages = ['en', 'de']; // Указываем, какие языки будем использовать в приложении.
    public static $languagesm = ['en'=>'English', 'de'=>'German']; // Указываем, какие языки будем использовать в приложении.


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url_array = explode('/',$request->path());
        $locale = $url_array[0];

        if (in_array($locale, self::$languages)) {
            App::setLocale($locale);
        }else{
            App::setLocale(self::$mainLanguage);
        }
        return $next($request);
    }


    public static function getLocale()
    {
        $uri = Request::path();
        $segmentsURI = explode('/',$uri);

        if (!empty($segmentsURI[0]) && in_array($segmentsURI[0], self::$languages)) {

            Session::put('locale',$segmentsURI[0]);
            return $segmentsURI[0];
        }else{
            Session::put('locale','en');
            return '';
        }
    }
}
