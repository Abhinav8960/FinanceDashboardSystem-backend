<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'viewer';
        $user->save();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'User registered successfully',
            'token' => $token,
            // 'user' => $user
        ], 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }


        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {

            $user = User::find(Auth::user()->id);
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'name' => $user->name,
                'id' => $user->id,
                'role' => $user->role,
            ], 200);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Either Email/Password is incorrect'
            ], 401);
        }
    }
}
