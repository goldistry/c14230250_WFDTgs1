<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Netflix',
                'website' => 'https://www.netflix.com/',
                'monthly_price' => '$5.99',
                'yearly_price'  => '$49',
                'logo' => 'streaming/netflix.png'
            ],
            [
                'name' => 'WeTV',
                'website' => 'https://wetv.vip/en',
                'monthly_price' => '$4.99',
                'yearly_price'  => '$39.99',
                'logo' => 'streaming/wetv.png'
            ],
            [
                'name' => 'Disney+ Hotstar',
                'website' => 'https://www.hotstar.com/',
                'monthly_price' => '$7.99',
                'yearly_price'  => '$59.99',
                'logo' => 'streaming/disneyplus.png'
            ],
        ];

        foreach ($plans as $plan) {
            Subscription::create([
                'id'            => Str::uuid(),
                'name'          => $plan['name'],
                'website'       => $plan['website'],
                'monthly_price' => $plan['monthly_price'],
                'yearly_price'  => $plan['yearly_price'],
                'logo'          => $plan['logo'],
            ]);
        }
    }
}