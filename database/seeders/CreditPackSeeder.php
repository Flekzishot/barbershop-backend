<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreditPack;

class CreditPackSeeder extends Seeder
{
    public function run(): void
    {
        $packs = [
            ['name' => '20 crédits', 'credits' => 20, 'price_mad' => 20],
            ['name' => '100 crédits', 'credits' => 100, 'price_mad' => 90],
            ['name' => '500 crédits', 'credits' => 500, 'price_mad' => 400],
            ['name' => '1 000 crédits', 'credits' => 1000, 'price_mad' => 750],
        ];
        foreach ($packs as $p) {
            CreditPack::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
