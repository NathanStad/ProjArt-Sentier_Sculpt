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
        
        public function uploadFile(Request $request)
        {
            // Obtenez l'utilisateur par son ID
            $user = User::find($request->input('userId'));
        
            // Vérifiez que l'utilisateur existe
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
        
            // Vérifiez que la requête contient un fichier
            if ($request->hasFile('photoProfil')) {
                $file = $request->file('photoProfil');
                $path = $file->store('users', 'public');
                $fileName = $file->hashName(); // Obtenez le nom du fichier généré
        
                $trueFileName = '/storage/' . $path;
                $user->update([
                    'photo' => $trueFileName,
                ]);
        
                return response()->json(['message' => 'Photo updated successfully!', 'path' => $path, 'fileName' => $fileName]);
            }
        
            return response()->json(['message' => 'No file uploaded'], 400);
        }
        
        
}