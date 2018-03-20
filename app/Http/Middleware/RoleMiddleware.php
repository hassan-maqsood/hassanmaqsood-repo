<?php namespace App\Http\Middleware;

use Closure;

class RoleMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $role)
	{
        if(count($request->user())){
            if (trim($request->user()->role->role) == trim($role)) {
                return $next($request);

            }else{
                return redirect('/');
            }
        }
    }

}
