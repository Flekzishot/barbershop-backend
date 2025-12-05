<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $req) {
        $data = $req->validate([
            'role' => 'required|in:client,barber',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'role' => $data['role'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password'])
        ]);
        Wallet::create(['user_id' => $user->id, 'balance_credits' => 0]);

        // TODO: email/phone verification
        return response()->json(['message' => 'Inscription réussie. Vérifiez votre email.'], 201);
    }

    public function login(Request $req) {
        $data = $req->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $tokenResult = $user->createToken('api');
        return response()->json([
            'token' => $tokenResult->accessToken,
            'user' => ['id' => $user->id, 'role' => $user->role, 'name' => $user->name]
        ]);
    }

    public function logout(Request $req) {
        $req->user()->token()->revoke();
        return response()->json(['message' => 'Déconnecté']);
    }

    public function me(Request $req) {
        return response()->json($req->user());
    }

    public function forgotPassword(Request $req) {
        $req->validate(['email' => 'required|email']);
        // TODO: reset password
        return response()->json(['message' => 'Si le compte existe, email envoyé']);
    }
}
