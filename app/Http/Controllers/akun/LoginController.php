<?php

namespace App\Http\Controllers\akun;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use JWTAuth;

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

    public function showLoginForm()
    {
        return view('auth.login',['breadcrumb' => 'Login', 'halaman' => 'Halaman Login']);
    }
    
    // public function logout(){
    //     Auth::logout();
    //     return 'sukses logout';
        // $request->request->add(['plan' => $plan]);
        // User::create($request->all() + ['plan' => $plan]);
        // User::create(array_merge($request->all(), ['plan' => $plan]));
    // }


    public function logout()
    {
        // Auth::logout();
        // return redirect('home');
        JWTAuth::invalidate();
        return response([
                'status' => 'success',
                'msg' => 'Logged out Successfully.'
            ], 200);
    }

    public function isSeller(){
        return $this->isSeller;
    }

    public function refreshToken(){
        try {
            $refreshedToken = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json([
                'status' => 'token_refreshed',
                'token' => $refreshedToken
            ], 200);
        } catch (TokenExpiredException $ex) {
            return response()->json([
                'status'    => 'error',
                'type'      => 'danger',
                'message'   => 'need_login',
                'button'    => [
                    1 => [
                        'value' => 'Login',
                        'link'  => route('loginapi'),
                        'method' => 'post'
                        ]
                    ]
                ], 401);
        }     
    }
}
