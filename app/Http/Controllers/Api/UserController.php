<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json([
                'status' => false,
                'message' => "User not found!"
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);

        if ($user == null) {
            return response()->json([
                'status' => false,
                'message' => "User not found!"
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'status' => 'required|in:0,1',
            'role' => 'required|in:viewer,analyst,admin'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->status = $request->status;
        $user->role = $request->role;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User data Updated Successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user == null) {
            return response()->json([
                'status' => false,
                'message' => "User not found!"
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => "User deleted successfully"
        ], 200);
    }
}
