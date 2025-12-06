<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPack extends Model
{
    protected $fillable = ['name','credits','price_mad','active'];
    protected $casts = ['active' => 'boolean'];
}
