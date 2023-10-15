<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\MatchOldPassword;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ResponseTrait;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $this->successResponse($user, 'Register success', 200);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if (!Auth::guard("web")->attempt($request->only('email', 'password'))) {
            return $this->failedResponse('Your credentials are wrong', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(
                [
                    'success' => true,
                    'message' => 'Login success',
                    'data' => [
                        'user' => $user,
                        'token' => $token
                    ]
                ],
                200
            )->withCookie('auth_token', $token, 60);
        ;
    }


    public function profile()
    {
        $user = Auth::user();

        return $this->successResponse($user, null, 200);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => 'required|string|min:8',
            'new_password_confirm' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return $this->successResponse(null, 'Password updated successfully', 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->successResponse(null, 'You have successfully logged out and the token was successfully deleted', 200);
    }

    public function notLogin()
    {
        return $this->failedResponse('You are not logged in', 401);
    }
}