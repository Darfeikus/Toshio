<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Firebase\JWT\ExpiredException;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, $role)
    {
        return $this->authenticate($request, $next, $role);
    }

    protected function authenticate($request, $next, $role)
    {
        try{
            $user = User::getFromToken($request->header('Authorization'));
        }catch (ExpiredException $e) {
            return $this->redirectTo($request);
        }catch (\UnexpectedValueException $e){
            return $this->redirectTo($request);
        }
        
        if(strcmp($user->getRole(),$role) != 0){
            return $this->redirectTo($request);
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        return redirect()->route('unauthorized');
    }
}
