<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TokenController extends Controller{
    public function user(Request $request)
    {
        $user = User::where('email', $request->user()->email)->first();
       
        return response()->json([
            "success" => true,
            "user"    => $request->user(),
            "roles"   => [$user->role->name],
        ]);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            // Get user
            $user = User::where([
                ["email", "=", $credentials["email"]]
            ])->firstOrFail();
            // Revoke all old tokens
            $user->tokens()->delete();
            // Generate new token
            $token = $user->createToken("authToken")->plainTextToken;
            // Token response
            return response()->json([
                "success"   => true,
                "authToken" => $token,
                "tokenType" => "Bearer"
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Invalid login credentials"
            ], 401);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    
        return response()->json([
            "success" => true,
            "message" => "User logged out successfully"
        ], 200);
        
       }
    
       public function register(Request $request){
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_id' => 1
            ]);
    
            // Generate token for the registered user
            $token = $user->createToken("authToken")->plainTextToken;
    
            return response()->json([
                "success" => true,
                "user" => $user,
                "username"=> $data["name"],
                "email"=> $data["email"],
                "authToken" => $token,
                "tokenType" => "Bearer"
            ], 200);
    
        
       }

}
