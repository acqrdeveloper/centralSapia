<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('database');
        $this->middleware('guest')->except('logout');
    }

    public function fnDoLogin(Request $request)
    {
//        dd($request->proyecto);
//        switch ($request->proyecto) {
//            case "0" :
////                config("database.default","mysql");
////                Config::set('database.default', "mysql");
////                DB::connection('mysql');
//                break;
//            case "1":
////                config("database.default","connect_interbank");
////                Config::set('database.default', "connect_interbank");
////                DB::connection('connect_interbank');
//                break;
//            case 2:
////                config("database.default","connect_corporativo");
////                Config::set('database.default', "connect_corporativo");
////                DB::connection('connect_corporativo');
//                break;
//        }

//        dd(config("database.default"));

//        $emailToArray = explode("@", $request->email);
//        if (isset($emailToArray[1])) {
//            $email = $request->email . "@sapia.com.pe";
//        } else {
//        }

        $email = $request->email;
        $pwd = $request->password;

        $this->validate($request, [$this->username() => 'required|string', 'password' => 'required|string']);
        // Process login
        $credentials = ["email" => $email, "password" => $pwd];
        $rememberme = $request->has('rememberme') ? true : false;
        if ($this->guard()->attempt($credentials, $rememberme)) {
//            session(["mydatabase" =>auth()->user()->database]);
            if (auth()->once($credentials)) {
                switch (auth()->user()->status) {
                    case 'I':
                        $this->guard()->logout();
                        $request->session()->invalidate();
                        return redirect()->to('/login')->withInput()->withErrors('Your session has expired because your account is deactivated.');
                        break;
                    default:
//                        $request->session()->regenerate();
//                        $this->clearLoginAttempts($request);
                        // Redirect page login

                        if ($this->attemptLogin($request)) {
                            return $this->sendLoginResponse($request);
                        }
                        break;
                }
            }
        }
        return $this->sendFailedLoginResponse($request);
    }

    public function fnDoLogout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->to('/login');
    }

}
