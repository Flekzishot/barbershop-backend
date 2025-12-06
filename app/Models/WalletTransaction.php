<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id','type','credits','price_mad','processor','reference','metadata'
    ];

    protected $casts = ['metadata' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
}
