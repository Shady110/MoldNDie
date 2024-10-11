<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create API token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return token, user ID, and user data
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->user_id,
            'user' => $user
        ]);
    }

    // User Signup (Registration)
   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:8',
        'email' => 'required|string|email|max:255|unique:users',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone_number' => 'required|string|regex:/^[0-9\+]*$/|digits_between:7,20',
        'country' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Create a new user
    $user = User::create([
        'username' => $validator->validated()['username'],
        'password' => Hash::make($validator->validated()['password']),
        'email' => $validator->validated()['email'],
        'first_name' => $validator->validated()['first_name'],
        'last_name' => $validator->validated()['last_name'],
        'phone_number' => $validator->validated()['phone_number'],
        'country' => $validator->validated()['country'],
    ]);

    // Create API token for the user
    $token = $user->createToken('auth_token')->plainTextToken;

    // Return the token, user ID, and user data (excluding password)
    return response()->json([
        'message' => 'User registered successfully',
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user_id' => $user->user_id,
        'user' => [
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'country' => $user->country,
        ]
    ], 201);
}

    // User Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // View authenticated user details
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Return all users
    public function index()
    {
        $users = User::all(); // Fetch all users
        return response()->json($users);
    }

    // Return a user with a specific ID
    public function show($id)
    {
        $user = User::findOrFail($id); // Find user by ID
        return response()->json($user);
    }

    // Other CRUD functions for user management...
}
