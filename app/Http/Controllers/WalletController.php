<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\CreditPack;

class WalletController extends Controller
{
    public function show(Request $req) {
        $wallet = Wallet::firstOrCreate(['user_id' => $req->user()->id], ['balance_credits' => 0]);
        $tx = WalletTransaction::where('user_id', $req->user()->id)->latest()->limit(50)->get();
        return response()->json(['wallet' => $wallet, 'transactions' => $tx]);
    }

    public function packs() {
        return CreditPack::where('active', true)->get();
    }

    public function purchase(Request $req) {
        $data = $req->validate([
            'pack_id' => 'required|exists:credit_packs,id',
            'processor' => 'required|in:stripe,paypal'
        ]);
        $pack = CreditPack::findOrFail($data['pack_id']);
        // TODO: processors + webhook verification
        $wallet = Wallet::firstOrCreate(['user_id' => $req->user()->id], ['balance_credits' => 0]);
        $wallet->increment('balance_credits', $pack->credits);
        WalletTransaction::create([
            'user_id' => $req->user()->id,
            'type' => 'purchase',
            'credits' => $pack->credits,
            'price_mad' => $pack->price_mad,
            'processor' => $data['processor'],
            'reference' => 'manual-dev',
            'metadata' => ['dev' => true]
        ]);
        return response()->json(['wallet' => $wallet]);
    }

    public function spend(Request $req) {
        $data = $req->validate([
            'credits' => 'required|integer|min:1',
            'reason' => 'required|string'
        ]);
        $wallet = Wallet::firstOrCreate(['user_id' => $req->user()->id], ['balance_credits' => 0]);

        if ($wallet->balance_credits < $data['credits']) {
            return response()->json(['message' => 'Crédits insuffisants'], 402);
        }
        $wallet->decrement('balance_credits', $data['credits']);
        WalletTransaction::create([
            'user_id' => $req->user()->id,
            'type' => 'spend',
            'credits' => $data['credits'],
            'processor' => 'internal',
            'reference' => $data['reason'],
        ]);
        return response()->json(['wallet' => $wallet]);
    }
}
