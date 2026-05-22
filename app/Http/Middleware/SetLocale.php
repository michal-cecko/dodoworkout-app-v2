<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach (LocaleService::getLocaleOptions(skipDefault: true) as $locale) {
            if ($request->route()->named(strtolower($locale->value) . '.*')) {
                App::setLocale(strtolower($locale->value));
                return $next($request);
            }
        }

        App::setLocale(config("app.locale"));

        return $next($request);
    }
}
