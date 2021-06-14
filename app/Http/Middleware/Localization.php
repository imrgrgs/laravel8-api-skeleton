<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Locale;
use App\Utils\ResponseUtil;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Response;

class Localization
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Check header request and determine localizaton
        $local = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : app()->getLocale();
        if (!$local) {
            $local = app()->getLocale();
        }
        // criar param locales
        if (!in_array($local, Locale::$allowedLocales)) {
            $error = __('messages.locale_not_allowed', ['attribute' => $request->header('X-localization'), 'allowed' => implode(', ', Locale::$allowedLocales)]);
            return $this->sendError(
                __($error, ['model' => __('models/users.singular')])
            );
            return Response::json(ResponseUtil::makeError($error), 400);
        }
        // set laravel localization
        app()->setLocale($local);
        // continue request
        return $next($request);
    }
}
