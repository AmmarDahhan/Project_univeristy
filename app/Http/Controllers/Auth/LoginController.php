<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function Login(Request $request){
        $filepath = 'C:\Users\G.force\Desktop\pharmacy.json';
        $flecontent = file_get_contents($filepath);
        $jsoncontent = json_decode($flecontent , true);
        $username = $request->input('username');
        $phone = $request->input('phone');
        foreach ($jsoncontent as $item)
                if($phone == $item['phone'] ){
            return response()->json([
                $item
            ]);

    }
}

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('message', 'تم تسجيل الخروج بنجاح.');
    }
}
