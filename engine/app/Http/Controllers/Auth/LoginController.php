<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->session()->flush();
        $rules = array(
            'username' => 'required',
            'password' => 'required'
        );
        $validator = validator($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        } else {
            $userdata = array(
                'username'     => $request->username,
                'password'  => $request->password
            );
            if (Auth::guard()->attempt($userdata)) {
                return redirect()->intended('/')->with('message', 'Login Sukses');
            } else {
                return redirect()->back()->with('error', 'Login Ulang, name atau Password anda salah');
            }
        }
    }
}
