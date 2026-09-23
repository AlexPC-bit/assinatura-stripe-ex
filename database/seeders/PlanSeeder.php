<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::firstOrCreate([
            'name' => 'Plano Básico',
            'stripe_price_id' => 'price_1UHJEUJiJeZBmdShz5OyXnCz', 
            'price' => 29.90,
            'interval' => 'month',
        ]);

        Plan::firstOrCreate([
            'name' => 'Plano Pro',
            'stripe_price_id' => 'price_1UHJEtJiJeZBmdShyKavICT7', 
            'price' => 59.90,
            'interval' => 'month',
        ]);
    }
}
