<?php

use App\Models\Translation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id')->index()->unique();
            $table->char('url')->nullable();
            $table->char('hover')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
//        factory(App\Models\Country::class, 10)->create();
        factory(App\Models\Category::class, 6)->create()->each(function (
            $category,
            $i
        ) {
            switch ($i) {
                case 0:
                    $url
                        = '/images/SearchCarPartsCategories/carrosserie_car_classic_parts_finder.png';
                    $hover = 'carosserie';
                    $name = 'Carrosserie';
                    $nameEN = 'Bodywork';
                    $nameDE = 'Karosseriebau';
                    break;
                case 1:
                    $url
                        = '/images/SearchCarPartsCategories/transmission_car_classic_parts_finder.png';
                    $hover = 'transmission';
                    $name = 'Transmission - Freinage';
                    $nameEN = 'Transmission';
                    $nameDE = 'Übertragung';
                    break;
                case 2:
                    $url
                        = '/images/SearchCarPartsCategories/moteur_car_classic_parts_finder.png';
                    $hover = 'moteur';
                    $name = 'Moteur';
                    $nameEN = 'Engine';
                    $nameDE = 'Motor';
                    break;
                case 3:
                    $url
                        = '/images/SearchCarPartsCategories/liaison_car_classic_parts_finder.png';
                    $hover = 'liaison';
                    $name = 'Liaison au sol';
                    $nameEN = 'Chassis';
                    $nameDE = 'Bodenverbindungsstelle';
                    break;
                case 4:
                    $url
                        = '/images/SearchCarPartsCategories/interieur_car_classic_parts_finder.png';
                    $hover = 'interieur';
                    $name = 'Intérieur';
                    $nameEN = 'Interior';
                    $nameDE = 'Innenraum';
                    break;
                case 5:
                    $url
                        = '/images/SearchCarPartsCategories/documentation_car_classic_parts_finder.png';
                    $hover = 'documentation';
                    $name = 'Documentation - Accessoires';
                    $nameEN = 'Documentation';
                    $nameDE = 'Dokumentation';
                    break;
            }
            $category->hover = $hover;
            $category->url   = $url;
            $category->save();
            factory(Translation::class)->create([
                'type'  => $category->getTable(), 'type_id' => $category->id,
                'fr_FR' => $name,
                'en_EN' => $nameEN,
                'de_DE' => $nameDE,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
