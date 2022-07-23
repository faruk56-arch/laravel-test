<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $local = $request->wantsJson() ? $this->fromJson($request) : $this->fromWeb($request);
        app()->setLocale($local);
        Carbon::setLocale($local);

        if (!$request->wantsJson()) {
            $request->route()->forgetParameter('locale');
        }

        return $next($request);
    }


    public function fromWeb(Request $request): ?string
    {
        $prefix = $request->segment(1);
        if ($prefix) {
            return $prefix;
        }
        $prefered = $request->getPreferredLanguage(['fr', 'en',
//            'de'
        ]);
        if ($prefered) {
            return $prefered;
        }
        return config('app.locale');
    }

    public function fromJson(Request $request): ?string
    {
        if ($request->hasHeader('X-localization')) {
            return $request->header('X-localization');
        }
        if ($request->hasHeader('accept-language')) {
            return $request->header('accept-language');
        }
        return env('APP_LOCALE');
    }
}
