<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Wallet;

class ListingController extends Controller
{
    public function index(Request $req) {
        $query = Listing::query()->where('status', 'active');

        if ($v = $req->query('visibility')) $query->where('visibility', $v);
        if ($type = $req->query('type')) $query->where('type', $type);

        return $query->orderBy('boosted_until', 'desc')->paginate(20);
    }

    public function store(Request $req) {
        $user = $req->user();
        $data = $req->validate([
            'title' => 'required|string|max:120',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:salon,domicile',
            'photos' => 'nullable|array'
        ]);

        $listing = Listing::create(array_merge($data, [
            'barber_id' => $user->id,
            'visibility' => 'standard',
            'status' => 'active'
        ]));

        return response()->json($listing, 201);
    }

    public function show($id) {
        return Listing::findOrFail($id);
    }

    public function update(Request $req, $id) {
        $listing = Listing::findOrFail($id);
        // TODO: policies
        $listing->update($req->all());
        return response()->json($listing);
    }

    public function destroy(Request $req, $id) {
        $listing = Listing::findOrFail($id);
        // TODO: policies
        $listing->delete();
        return response()->json(['message' => 'Supprimé']);
    }

    public function boost(Request $req, $id) {
        $listing = Listing::findOrFail($id);
        $user = $req->user();

        $duration = $req->validate(['duration' => 'required|in:24,72,168'])['duration'];
        $costs = ['24' => 20, '72' => 50, '168' => 100];

        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();
        if ($wallet->balance_credits < $costs[$duration]) {
            return response()->json(['message' => 'Crédits insuffisants'], 402);
        }

        $wallet->decrement('balance_credits', $costs[$duration]);
        $listing->visibility = 'boosted';
        $listing->boosted_until = now()->addHours((int)$duration);
        $listing->save();

        return response()->json($listing);
    }
}
