<?php

use App\Models\Category;
use App\Models\Modele;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class PartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Part::class, 60)->create()->each(function ($part) {
            $modele = Modele::all()->random(1)->first();
//            $part->modele_id = $modele->id;
//            $categories = $modele->categories()->get()->toArray();
            $category = Category::all()->random(1)->first();
            $part->category_id = $category->id;
            $part->save();
            factory(Translation::class)->create(
                [
                    'type' => 'parts',
                    'type_id' => $part->id,
                ]
            );
        });
    }
}
