<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
        $this->middleware('guest')->except('logout');
    }

    public function fnDoLogin(Request $request)
    {
        $email = "";
        $pwd = "";
        $emailToArray = explode("@", $request->email);
        if (!isset($emailToArray[1])) {
            $email = $request->email . "@sapia.com.pe";
        } else {
            $email = $request->email;
        }
        $pwd = $request->password;
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        // Process login
        $credentials = ["email" => $email, "password" => $pwd];
        $rememberme = $request->has('rememberme') ? true : false;
        if ($this->guard()->attempt($credentials, $rememberme)) {
            if (auth()->once($credentials)) {
                switch (auth()->user()->status) {
                    case 'I':
                        $this->guard()->logout();
                        $request->session()->invalidate();
                        return redirect()->to('/login')->withInput()->withErrors('Your session has expired because your account is deactivated.');
                        break;
                    default:
                        $request->session()->regenerate();
                        $this->clearLoginAttempts($request);
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
