<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
// use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->role = 'user';
            $user->save();

            Auth::login($user);

            return response()->json([
                'message' => 'User registered successfully!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Exception occurred: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while registering user.',
            ], 500);
        }
    }
   
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            if (!$token = auth()->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->createNewToken($token);
        } catch (\Exception $e) {
            // Handle any errors that occur
            Log::error($e->getMessage());
            return response(['message' => 'An error occurred while creating token for user user login.'], 501);
        }
    }

    public function createNewToken($token)
    {
        try {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 201);
        } catch (\Exception $e) {
            // Handle any errors that occur
            Log::error($e->getMessage());
            return response(['message' => 'An error occurred while creating token for user user login in createNewToken.'], 500);
        }
    }
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully logged out.']);
    }
    // public function getAllUser(Request  $request)
    // {
    //     $user = Auth::user();

    //     try {
    //         if ($user->role === 'admin') {
    //         $user = User::All();
    //         if ($user->count() > 0) {
    //             return $user;
    //         }
    //     }
    //         return response()->json(['message' => 'no records found'], 404);
    //     } catch (\Exception $e) {
    //         Log::error($e->getMessage());
    //         return response(['message' => 'An error occurred while fetching All users.'], 501);
    //     }
    // }
    public function getAllUser(Request $request)
{
    try {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $users = User::all();

        if ($users->count() > 0) {
            return $users;
        } else {
            return response()->json(['message' => 'No records found'], 404);
        }
    } catch (JWTException $e) {
        Log::error($e->getMessage());
        return response(['message' => 'An error occurred while fetching all users.'], 500);
    }
}

}
