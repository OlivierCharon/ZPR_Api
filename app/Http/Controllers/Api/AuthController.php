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
        if (auth()->user()->is_admin) {
            try {
                $user = new User();
                $user->name = $request->name;
                $user->password = $request->password;
                $user->email = $request->email;
                $user->img = $request->img;
                $user->is_admin = $request->isAdmin ?? false;
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
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to create users'
        ]);
    }

    public function store(LoginUserRequest $request)
    {
        if (Auth::attempt(['email' => $request->login, 'password' => $request->password]) || Auth::attempt(['name' => $request->login, 'password' => $request->password])) {
            $user = Auth::user();
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

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->user_id === auth()->user()->id || auth()->user()->is_admin) {
            try {
                $user = [...$request];
                // $user->name = $request->name ?? $user->name;
                // $user->password = $request->password ?? $user->password;
                // $user->email = $request->email ?? $user->email;
                // $user->img = $request->img ?? $user->img;
                // $user->admin = $request->admin ?? $user->admin ?? false;
                // $user->active = $request->active ?? $user->active ?? true;
                $user->updated_by = auth()->user()->id;
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
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to update users'
        ]);
    }

    public function enable(User $user, Request $request)
    {
        if (auth()->user()->is_admin) {
            try {
                $user->active = $request->enable;
                $user->updated_by = auth()->user()->id;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => $request->enable === true ? 'User enabled' : 'User disabled',
                    'data' => $user
                ]);
            } catch (Exception $e) {
                return response()->json($e);
            }
        }
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to disable or enable users'
        ]);
    }

    public function destroy(User $user, Request $request)
    {
        if (auth()->user()->is_admin) {
            try {
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
        return response()->json([
            'status' => 500,
            'message' => 'You have no right to delete users'
        ]);
    }
}
