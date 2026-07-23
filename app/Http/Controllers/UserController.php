<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    //Create User
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'user_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);


        if ($request->hasFile('user_image')) {
            $fileName = $request->file('user_image')->store('user-images', 'public');
        } else {
            $fileName = null;
        }
        $user->user_image = $fileName;
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
            return response()->json(["Error: " => $exception->getMessage()]);
        }
    }

    //Read all users
    public function readUsers()
    {
        try {
            // $users = User::all();
            $users = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->select('users.*', 'roles.name as role_name')
                ->get();
            return response()->json($users);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Failed to Fetch Users',
                'error' => $exception->getMessage()
            ]);
        }
    }

    //Read (id) specific record
    public function readUser($id)
    {
        try {
            // $user = User::where('id',$id)->first();            
            $user = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.id', $id)
                ->select('users.*', 'roles.name as role_name')
                ->first();
            return response()->json($user);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Failed to Fetch User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    //Update user(id)
    public function updateUser($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'user_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        //Fetch user(id) to fill the User model
        $existingUser = User::where('id', $id)->first();
        $existingUser->name = $validated['name'];
        $existingUser->email = $validated['email'];
        $existingUser->password = Hash::make($validated['password']);
        $existingUser->user_image = $validated['user_image'];
        $existingUser->role_id = $validated['role_id'];

        try {
            $existingUser->save();
            return response()->json([
                'message' => 'User updated successfully!',
                'user' => $existingUser
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Failed to Update User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    //Delete user function
    public function deleteUser($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if ($user) {
                $user->delete();
                return response()->json("User Deleted Successfully!");
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Failed to Delete User',
                'error' => $exception->getMessage()
            ]);
        }
    }
}
