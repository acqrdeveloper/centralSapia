<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
    protected $redirectTo = '/';

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
        $emailToArray = explode("@", $request->email);
        if (isset($emailToArray[1])) {
            $request->email = $request->get('email');
        } else {
            $request->email = $emailToArray[0] . "@sapia.com.pe";
        }
        $this->validate($request, [$this->username() => 'required|string', 'password' => 'required|string']);
        // Process login
        $credentials = ['email' => $request->email, 'password' => $request->password];
        $rememberme = $request->has('rememberme') ? true : false;
        User::where('email', $request->email)->update(['api_token' => Hash::make(100)]);
        if ($this->guard()->attempt($credentials, $rememberme)) {
            if (auth()->once($credentials)) {
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
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
