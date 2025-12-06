<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarberProfile;

class ProfileController extends Controller
{
    public function show(Request $req) {
        $user = $req->user();
        $profile = BarberProfile::where('user_id', $user->id)->first();
        return response()->json(['user' => $user, 'barberProfile' => $profile]);
    }

    public function update(Request $req) {
        $user = $req->user();
        $data = $req->validate([
            'salon_name' => 'nullable|string|max:120',
            'description' => 'nullable|string',
            'photos' => 'nullable|array',
            'services' => 'nullable|array',
            'availability' => 'nullable|array',
            'geo_lat' => 'nullable|numeric',
            'geo_lng' => 'nullable|numeric',
            'category' => 'nullable|in:homme,femme,enfant,mixte'
        ]);

        $profile = BarberProfile::updateOrCreate(['user_id' => $user->id], $data);
        return response()->json(['barberProfile' => $profile]);
    }
}
