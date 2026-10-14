<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/** Serves redirects managed in the CMS (PRD §8.4: permanent redirects for changed URLs). */
class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe() || $request->is('admin', 'admin/*')) {
            return $next($request);
        }

        $match = Redirect::map()[Redirect::normalize($request->getPathInfo())] ?? null;

        if (! $match) {
            return $next($request);
        }

        [$to, $status, $id] = $match;

        DB::table('redirects')->where('id', $id)->update(['hits' => DB::raw('hits + 1'), 'last_hit_at' => now()]);

        $target = str_starts_with($to, 'http') ? $to : url($to);
        if ($query = $request->getQueryString()) {
            $target .= (str_contains($target, '?') ? '&' : '?').$query;
        }

        return redirect()->away($target, $status);
    }
}
