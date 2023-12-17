<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create(RegisterUserRequest $request)
    {
        // if (auth()->user()->is_admin) {
            try {
                $user = new User();
                $user->name = $request->name;
                $user->password = $request->password;
                $user->email = $request->email;
                $user->img = $request->img;
                $user->is_admin = $request->isAdmin ?? false;
                $user->save();
                // Log in the user
                Auth::login($user);
                $request->session()->regenerate();

                // $user->sendEmailVerificationNotification();

                return response()->json([
                    'success' => true,
                    'status' => 201,
                    'message' => 'User registered',
                    'user' => [
                        'id'=> $user->id,
                        'email'=> $user->email,
                        'name'=> $user->name,
                        'img'=> $user->img,
                        'is_admin'=> $user->is_admin,
                    ]
                ]);
            } catch (Exception $e) {
                return response()->json($e);
            }
            return response()->json([
                'status' => 500,
                'message' => 'You have no right to create users'
            ]);
            // }
        }

        public function store(LoginUserRequest $request)
        {
            if (Auth::attempt(['email' => $request->login, 'password' => $request->password],true) ||
            Auth::attempt(['name' => $request->login, 'password' => $request->password],true)) {
                $request->session()->regenerate();

                $user = Auth::user();
                // $token =  $user->createToken(env('DB_SALT'))->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'message' => 'User logged successfully',
                    'user' => [
                        'id'=> $user->id,
                        'email'=> $user->email,
                        'name'=> $user->name,
                        'img'=> $user->img,
                        'is_admin'=> $user->is_admin,
                    ]
                    // 'token' => $token
                ]);
            }
            throw ValidationException::withMessages(['Authentication failled']);
            // return response()->json([
            //     'status' => 401,
            //     'message' => 'Wrong login or password'
            // ]);

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
                        'user' => $user
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
                        'user' => $user
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

        public function remove(User $user, Request $request)
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

            public function current()
            {
                if(Auth::user())
                    return response()->json([
                        'user'=>[
                            'id' => Auth::user()->id,
                            'email' => Auth::user()->email,
                            'name' => Auth::user()->name,
                            'img' => Auth::user()->img,
                            'is_admin' => Auth::user()->is_admin,
                        ]
                    ]);
                else
                    return false;
            }

            public function destroy(Request $request)
            {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect();
            }
        }
