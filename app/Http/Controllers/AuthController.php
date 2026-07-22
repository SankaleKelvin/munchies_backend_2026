<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //Registration function
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'role_id'=>'required|integer|exists:roles,id'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role_id = $validated['role_id'];

        try {
            $user->save();

            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $user->id,
                    'hash' => sha1($user->email)
                ]
            );

            $user->notify(new VerifyEmailNotification($signedUrl));

            return response()->json([
                'Message' => 'A verification link has been sent to your email address.'
            ], 200);
        } catch (Exception $exception) {
            return response()->json(["Error: "=> $exception->getMessage()]);
        }
    }

    //Login function
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:3'
        ]);

        try {
            $user = User::where('email', $validated['email'])->first();
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => 'The provided credentials are incorrect.'
                ]);
            }

            if(!$user->is_active){
                return response()->json([
                    'message'=>'Your account is not activated, please verify your email.'
                ]);
            }

            $token = $user->createToken('auth-key')->plainTextToken;

            return response()->json([
                'message' => 'Login Successful!',
                'User' => $user,
                'token' => $token
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Login Failed',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function userInfo()
    {
        try {
            $user = Auth::user();
            return response()->json($user);
        } catch (Exception $exception) {
            return response()->json("Error getting user info: ", $exception->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response("Logout Successful");
    }
}
