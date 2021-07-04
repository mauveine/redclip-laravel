<?php

namespace App\Http\Middleware;

use Closure;
use Faker\Factory;
use Illuminate\Http\Request;

class EnsureAnonymousName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $faker = Factory::create();
        if (!session()->get('username')) {
            session()->put('username', $faker->unique()->userName . $faker->unique()->uuid);
        }
        return $next($request);
    }
}
