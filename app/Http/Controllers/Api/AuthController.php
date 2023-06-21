<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $user->save();

            // $user->sendEmailVerificationNotification();
            
            return response()->json([
                'status'=>201,
                'message'=>'User registered',
                'data'=>$user
            ]);
        } catch (Exception $e){
            return response()->json($e);
        }
    }

    public function store(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            $success['token'] =  $user->createToken(env('DB_SALT'))->plainTextToken; 
            $success['name'] =  $user->name;
            return response()->json([
                'status'=>200,
                'message'=>'User login successfully',
                'user'=>$user
            ]);
        } 
        else{ 
            return response()->json([
                'status'=>401,
                'message'=>'Login failed'
            ]);
        } 

    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'status'=>200,
                'message'=>'User deleted'
            ]);
            
        } catch (Exception $e){
            return response()->json($e);
        }
    }
}