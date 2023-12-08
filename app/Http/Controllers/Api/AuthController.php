<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Auth\Events\Registered;
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
                'success' => true,
                'status' => 201,
                'message' => 'User registered',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function store(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] =  $user->createToken(env('DB_SALT'))->plainTextToken;
            $success['name'] =  $user->name;
            return response()->json([
                'status' => 200,
                'message' => 'User login successfully',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login failed'
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
