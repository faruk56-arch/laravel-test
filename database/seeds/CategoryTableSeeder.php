<?php

use App\Models\Translation;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Category::class, 12)->create()->each(function ($category) {
            factory(Translation::class)->create(
                [
                    'type' => 'category',
                    'type_id' => $category->id,
                ]
            );
        });
    }
}
