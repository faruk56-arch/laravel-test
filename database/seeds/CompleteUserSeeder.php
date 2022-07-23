<?php

use App\Models\Category;
use App\Models\Merchant;
use App\Models\Modele;
use App\Models\Part;
use App\Models\Product;
use App\Models\Research;
use App\Models\Sale;
use App\Models\Translation;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class CompleteUserSeeder extends Seeder
{
    /**      * Run the database seeds.      *      * @return void */
    public function run()
    {
        $faker = \Faker\Factory::create('fr_FR');
        factory(App\Models\Brand::class, 3)->create()->each(function ($brand) {
            factory(App\Models\Modele::class, 2)
                ->create(['brand_id' => $brand->id])->each(function (
                    Modele $model
                ) {
                    $model->categories()->attach(Category::all());
                    factory(App\Models\Part::class, 20)->create()
                        ->each(function (
                            Part $part,
                            $i
                        ) {
                            $part->unsearchable();
                            if ($i > 5) {
                                $part->category_id = Category::all()->random(1)
                                    ->first()->id;
                            } else {
                                $part->category_id = Category::all()->skip($i)
                                    ->first()->id;
                            }
                            $part->save();
                            factory(Translation::class)->create([
                                'type'    => $part->getTable(),
                                'type_id' => $part->id,
                            ]);
                            $part->searchable();
                        });

                    factory(App\Models\Part::class, 2)->create(['modele_id'=>$model->id])
                        ->each(function (
                            Part $part,
                            $i
                        ) {
                            if ($i > 5) {
                                $part->category_id = Category::all()->random(1)
                                    ->first()->id;
                            } else {
                                $part->category_id = Category::all()->skip($i)
                                    ->first()->id;
                            }
                            factory(Translation::class)->create([
                                'type'    => $part->getTable(),
                                'type_id' => $part->id,
                            ]);
                            $part->save();
                        });
                });
        });
        factory(App\Models\User::class)->create(['email' => 'test@test.fr', 'role'=>'admin'])
            ->each(function (User $user) use ($faker) {
                foreach (Modele::all() as $modele) {
                    $user->models()->save(
                        $modele,
                        ['car_name' => $faker->firstNameFemale]
                    );
                    $compatibles = Modele::where('id', '!=', $modele->id)
                        ->whereNotIn(
                            'id',
                            DB::table('model_compatible')->pluck('model_id')
                        )
                        ->whereNotIn(
                            'id',
                            DB::table('model_compatible')->pluck('model_id')
                        )
                        ->inRandomOrder()->take(random_int(1, 3))->get();
                    $modele->compatible()->saveMany($compatibles);
                    foreach ($compatibles as $i => $compatible) {
                        $compatible->compatible()
                            ->saveMany($compatibles->replace([$i => $modele]));
                    }
                    $part = Part::inRandomOrder()->first();
                    $car = UserModel::where(
                        'user_id',
                        $user->id
                    )->inRandomOrder()->first();
                    $products = factory(App\Models\Product::class, 5)->create(['part_id' => $part->id, 'stock'=>1, 'status'=>1]);
                    foreach ($products as $product) {
                        $product->modele()->save($modele);
                    }

                    factory(App\Models\Research::class, 1)->create(['status'=>null])
                        ->each(function (Research $research) use (
                            $user,
                            $part,
                            $car
                        ) {
                            $research->user()->associate($user);

                            $research->modele_id = $car->id;
                            $research->part_id = $part->id;

                            $research->save();
                        });
                }
                factory(App\Models\Research::class, 2)->create(['status'=>'in_progress'])
                    ->each(function (Research $research) use (
                        $user,
                        $part,
                        $car
                    ) {
                        $products = factory(App\Models\Product::class, 5)->create(['part_id' => $research->part_id, 'stock'=>1, 'status'=>1]);
                        foreach ($products as $product) {
                            $product->modele()->save(Modele::find($research->modele_id));
                        }
                        $research->products()->saveMany($products);
                    });
                $matches = Sale::all();
                $preferences = ['no', 'maybe', 'interested'];
                foreach ($matches as $match) {
                    $match->status_preference = $preferences[rand(0, 2)];
                    if (Sale::where('research_id', $match->research_id)
                            ->whereIn('status', [4, 5, 6, 7, 8, 9, 10, 11])->count() == 0
                    && $match->status_preference != 'no') {
                        $match->status = random_int(4, 11);
                        $match->status_note = $faker->text;
                    }
                    $match->save();
                }
                factory(App\Models\Merchant::class)->create()->each(
                    function (Merchant $merchant) use ($faker, $part, $user) {
                        $products = Product::all();
                        foreach ($products as $product) {
                            $product->merchant_id = $merchant->id;
                            $product->save();
                        }
                        $user->merchant_id = $merchant->id;
                        $user->merchant_role = 1;
                        $user->save();
                    }
                );
                factory(App\Models\AlertSubscription::class, 5)->create();
            });
    }
}
