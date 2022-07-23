<?php

use Illuminate\Database\Seeder;

class TranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Translation::class, 30)->create();
    }
}
