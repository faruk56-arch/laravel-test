<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 30)->create()->each(function ($x) {
            for ($i = 0; $i < random_int(1, 20); $i++) {
                $product = Product::all()->random(1)->first();
                $x->products()->save($product, ['quantity'=>random_int(1, 20)]);
            }
        });
    }
}
