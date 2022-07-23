<?php

use App\Models\Part;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Class ResearchTableSeeder.
 */
class ResearchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $status = ['no', 'maybe', 'interested'];
        factory(App\Models\Research::class, 800)->create()->each(
            function ($x) use ($status) {
                $product = Product::all()->random(1)->first();
                $part = Part::all()->random(1)->first();
                $x->products()->save(
                    $product,
                    ['status' => $status[array_rand($status)],
                        'status_note' => 'Ceci est une note', ]
                );
                $x->part_id = $part->id;
                $x->save();
            }
        );
    }
}
