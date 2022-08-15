<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;

class Logging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $audit = new Audit();
        if (Auth::user()) {
            $audit->user_type = "App\Models\User";
            $audit->user_id = Auth::user()->id;
        } else {
            $audit->user_type = "";
            $audit->user_id = 0;
        }
        $audit->event = "clicked";
        $audit->auditable_type = "Tracking Request";
        $audit->auditable_id = "0";
        $audit->old_values = [];
        $audit->new_values = $request->url();
        $audit->url = $request->url();
        $audit->ip_address = $request->ip();
        $audit->user_agent = $request->header('user-agent');
        $audit->tags = "";
        $audit->save();
        return $next($request);
    }
}
