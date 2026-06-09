<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority: URL segment → Session → Default setting → App default
        $locale = $this->resolveLocale($request);

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        $supported = Language::active()->pluck('code')->toArray();

        // 1. URL query param ?lang=xx
        if ($lang = $request->query('lang')) {
            if (in_array($lang, $supported)) {
                return $lang;
            }
        }

        // 2. Session
        if ($lang = Session::get('locale')) {
            if (in_array($lang, $supported)) {
                return $lang;
            }
        }

        // 3. Default language from settings
        $default = Language::where('is_default', true)->value('code');
        if ($default && in_array($default, $supported)) {
            return $default;
        }

        return config('app.locale', 'en');
    }
}
