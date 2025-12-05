<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberProfile extends Model
{
    protected $fillable = [
        'user_id','salon_name','description','geo_lat','geo_lng','category','photos','availability','services'
    ];

    protected $casts = [
        'photos' => 'array',
        'availability' => 'array',
        'services' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
