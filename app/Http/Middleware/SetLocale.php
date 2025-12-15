<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
	/**
	 * Handle an incoming request.
	 *
	 * Reads the preferred locale from the session and applies it to the application.
	 * Falls back to config('app.locale'). Only allows a safe whitelist of locales.
	 */
	public function handle(Request $request, Closure $next)
	{
		$supported = ['en', 'vi'];

		// Get locale from session, fallback to config
		// Use has() to check if session key exists, then get it
		$locale = null;
		if ($request->hasSession() && $request->session()->has('locale')) {
			$locale = $request->session()->get('locale');
		}

		if (!$locale) {
			// Optional: detect from browser and normalize to supported
			$browser = substr((string) $request->getPreferredLanguage($supported), 0, 2);
			$locale = in_array($browser, $supported, true) ? $browser : config('app.locale');
			if ($request->hasSession()) {
				$request->session()->put('locale', $locale);
			}
		}

		// Validate locale
		if (!in_array($locale, $supported, true)) {
			$locale = config('app.locale');
			if ($request->hasSession()) {
				$request->session()->put('locale', $locale);
			}
		}

		// Set locale for the application
		App::setLocale($locale);

		return $next($request);
	}
}

