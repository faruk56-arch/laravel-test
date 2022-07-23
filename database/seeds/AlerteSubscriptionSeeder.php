<?php

use App\Models\AlertSubscription;
use App\Models\Merchant;
use Illuminate\Database\Seeder;

class AlerteSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = factory(Merchant::class)->create();
        factory(AlertSubscription::class, 3)
            ->create(['merchant_id' => $merchant->id]);
    }
}
