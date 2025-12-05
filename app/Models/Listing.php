<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'barber_id','title','description','price','type','visibility','status','boosted_until','photos'
    ];

    protected $casts = [
        'photos' => 'array',
        'boosted_until' => 'datetime'
    ];

    public function barber() {
        return $this->belongsTo(User::class, 'barber_id');
    }
}
