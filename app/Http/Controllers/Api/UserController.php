<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterUserRequest;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->password = $request->password;
            $user->email = $request->email;
            $user->save();

            $user->sendEmailVerificationNotification();
            
            return response()->json([
                'status'=>201,
                'message'=>'User registered',
                'data'=>$user
            ]);
        } catch (Exception $e){
            return response()->json($e);
        }
    }

    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 

    }

    public function delete(User $user)
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
