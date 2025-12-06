<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // recuperation de l'utilisateur correspond
        $user = User::where('email', $credentials['email'])->first();

        // Renvoie d'un Message d'erreur si  y a pas de'user avec les credentials specifies
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /*if ($user->password !== $credentials['password']) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }*/

        //  Comparaison de Hashe de password avec celui dans la base de donnees (qui est hashe) ou lieu de compare les textes en  claire
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Faille de securite : Enregistrement des password en claire dans la base de donnees
            //'password' => $validated['password'],
            // Password Hashe avant de l'enregistrer en base
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
     * Get current user info.
     */
    public function me(Request $request)
    {
        $userId = $request->input('user_id');
        
        if (!$userId) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}

