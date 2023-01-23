<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user                       = Auth::user();
            $user["token"]              = $user->createToken('moviesApp')->accessToken;

            return new UserResource($user);
        } else { 
            return response()->json(['message' =>'Wrong Credentials'], 401);
        } 
    }

    public function register(UserRegisterRequest $request)
    {
        $data             = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);
        $user             = User::create($data);
        $user["token"]    = $user->createToken('moviesApp')->accessToken;
    
        return new UserResource($user);
    }
}
