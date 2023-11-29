<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class Register
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
//ICAgIHsKICAgICAgICAidXNlcm5hbWUiOiJtdXN0YWZhIiwKICAgICAgICAgImVtYWlsIjoibXVzdGFmYUBnbWFpbC5jb20iLAogICAgICAgICAicGFzc3dvcmQgIiA6ICAxMjM0NTY3ODkKCgogICAgfQ==

// {
//     "username":"ammar",
//      "email":"ammar@gmail.com",
//      "password " :  123456789


// }

// ICAgIHsKICAgICAgICAidXNlcm5hbWUiOiJhbW1hciIsCiAgICAgICAgICJlbWFpbCI6ImFtbWFyQGdtYWlsLmNvbSIsCiAgICAgICAgICJwYXNzd29yZCAiIDogIDEyMzQ1Njc4OQoKCiAgICB9

