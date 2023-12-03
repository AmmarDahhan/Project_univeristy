<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckToken
{
    private $allowed = [];
    public function __construct()
    {
        $filepath = 'C:\xampp\htdocs\my_website\Project_University\pharmacy.json';
        $flecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($flecontent , true);

        foreach ($jsoncontent as $user) {
            $this->allowed[] = $user['phone'];
        }
    }
    public function handle(Request $request, Closure $next): Response
    {
        $error = false;
        if (!$request->hasHeader('X_Token') || empty($request->header('X_Token'))) {
            $error = true;
        }
        try{
            $jsonPayLoad = json_decode(base64_decode($request->header('X_Token')), true);
            if(!$jsonPayLoad){
                $error = false;
            }
            if (!isset($jsonPayLoad['phone']) || empty($jsonPayLoad['phone'])) {
                $error = true;
            }
            if (!in_array($jsonPayLoad['phone'], $this->allowed)) {
                $error = true;
            }
        }
        catch(\Exception $exception){
            $error = true;
        }
        if($error){
            return response()->json([
                'message' => 'Invalid token or phone number',
            ], 401);
        }
        return $next($request);
    }
}
