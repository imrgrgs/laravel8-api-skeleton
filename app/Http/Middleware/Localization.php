<?php

namespace App\Http\Middleware;

use App\Models\Param;
use App\Services\ParamService;
use Closure;
use App\Utils\Locale;
use App\Utils\ResponseUtil;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
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

        /*|-------------------------------------------------------------------------
          | Leitura de params para obter os locales válidos param-name = 'locales'
          |-------------------------------------------------------------------------
          */
        $paramName = 'locales';
        $values = ParamService::getValuesByParamName($paramName);
        if (!$values->contains('code', $local)) {
            $allowedLocales = ParamService::getValuesCodeByParamName($paramName);
            $error = __('messages.locale_not_allowed', ['attribute' => $request->header('X-localization'), 'allowed' => implode(', ', $allowedLocales)]);

            return $this->sendError($error, JsonResponse::HTTP_PRECONDITION_FAILED);
        }

        // set laravel localization
        app()->setLocale($local);
        // continue request
        return $next($request);
    }
}
