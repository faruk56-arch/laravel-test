<?php

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ModeleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Modele::class, 100)->create()->each(function ($car) {
            $car->brand_id = Brand::all()->random(1)->first()->id;
            $max = random_int(1, 11);
            for ($i = 0; $i < $max; $i++) {//on rajoute entre 1 et 12 catégories au modele
                $car->categories()->save(Category::all()->random(1)->first());
            }
            $car->save();
        });
    }
}
