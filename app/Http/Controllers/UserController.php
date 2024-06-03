<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {

        public function index() {
            $users = User::all();
            
            return response()->json($users);
        }
    
        public function show($id) {
            $user = User::find($id);

            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['message' => 'User not found'], 404);
            }
        }

        public function store(UserRequest $request)
        {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role'),
                'photo' => $request->input('photo'),
            ]);
    
            return response()->json($user, 201);
        }
        
        public function authenticate(Request $request)
        {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
    
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
    
                return response()->json(['message' => 'Authenticated', 'user' => Auth::user()], 200);
            }
    
            return response()->json(['message' => 'Authentication failed'], 401);
        }
}