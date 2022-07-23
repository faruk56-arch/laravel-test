<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Modele;
use App\Models\Part;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class BrandModeleCategoryPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Brand::class)->create();
        factory(Modele::class)->create();
        $category = factory(Category::class)->create();
        factory(Translation::class)->create(
            [
                'type'    => 'categories',
                'type_id' => $category->id,
            ]
        );
        Category::all()->each(
            function ($category) {
                $category->modeles()->saveMany(Modele::all());
            }
        );
        $part = factory(Part::class)->create();
        factory(Translation::class)->create(
            [
                'type'    => 'parts',
                'type_id' => $part->id,
            ]
        );
        $part = $part->fresh();
        $part->searchable();
    }
}
