<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;

use App\model\User;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        try {
        // attempt to verify the credentials and create a token for the user
        // if (!$token = JWTAuth::attempt($credentials)) {
        if (!$request->token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
        // all good so return the token
        return response()->json([
            'email' => auth()->user()->email,
            'isSeller' => auth()->user()->isSeller
        ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    public function create()
    {
        $user = User::find(1)->user_data->first();
        return response()->json(['data' => $user], 200);
    }

    public function store(ProdukRequest $request)
    {
        // return $request;
        return response()->json(['data' => $request->all() ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
