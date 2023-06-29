<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function create(RegisterUserRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->password = $request->password;
            $user->email = $request->email;
            $user->img = $request->img;
            $user->save();

            // $user->sendEmailVerificationNotification();

            return response()->json([
                'status' => 201,
                'message' => 'User registered',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function store(LoginUserRequest $request)
    {
        if (Auth::attempt(['email' => $request->login, 'password' => $request->password]) || Auth::attempt(['name' => $request->login, 'password' => $request->password])) {
            // if (Auth()->attempt($request->only(['login', 'password']))) {
            $user = Auth::user();
            // $user = Auth()->user();
            $token =  $user->createToken(env('DB_SALT'))->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'User logged successfully',
                'user' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Wrong login or password'
            ]);
        }
    }

    public function destroy(User $user, Request $request)
    {
        try {
            // dd($user);
            $user->delete();
            if (!$user) {
                dd('pas de user');
                $query = Post::where([
                    ['lower(name)', 'LIKE', '%' . strtolower($request->name) . '%'],
                    ['lower(email)', 'LIKE', '%' . strtolower($request->email) . '%']
                ])->get();
                dd($query);
            }

            return response()->json([
                'status' => 200,
                'message' => 'User deleted'
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
