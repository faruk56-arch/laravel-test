<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditReferenceResearchProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function ($table) {
            $table->string('reference')->nullable()->after('status');
        });
        Schema::table('researches', function ($table) {
            $table->string('reference')->nullable()->after('status');
        });
        Schema::table('parts', function ($table) {
            if (DB::getDriverName() !== 'sqlite') {

                $table->dropForeign('parts_modele_id_foreign');
            }
        });
        Schema::table('parts', function ($table) {
            $table->dropColumn('reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function ($table) {
            $table->dropColumn('reference');
        });
        Schema::table('researches', function ($table) {
            $table->dropColumn('reference');
        });
        Schema::table('parts', function ($table) {
            $table->string('reference')->nullable();
//            $table->unsignedBigInteger('modele_id')->nullable();
            $table->foreign('modele_id')->references('id')->on('modeles');
        });
    }
}
