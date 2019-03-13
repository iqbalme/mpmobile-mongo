<?php

namespace App\Http\Controllers\akun;

use App\model\User;
use App\model\UserData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\model\Wilayah;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    public function showRegistrationForm()
    {
        $wilayah = Wilayah::distinct()->pluck('provinsi');
        return view('auth.register', compact('wilayah'));
    }

    public function register(Request $request)
    {
        $user = [
            'username'        => $request->username,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'level_id'        => 1, //$request->username,
            'last_visit'      => null, //date('Y-m-d H:i:s'),
            'api_token'       => null,
            'remember_token'  => null
        ];

        $adduser = User::create($user);
        $adduserdata = new UserData;
        $adduserdata->nama_lengkap      = $request->nama_lengkap;
        $adduserdata->display_name      = $request->display_name;
        $adduserdata->alamat            = $request->alamat;
        $adduserdata->kelurahan_id      = 15; //$request->kelurahan;
        $adduserdata->phone             = $request->phone;
        $adduserdata->kode_pos          = '90552'; //$request->kode_pos;
        $adduser->user_data()->save($adduserdata);
        return redirect('home');
    }
}
