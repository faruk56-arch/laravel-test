<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateModelCompatibleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->dropColumn('model_compatible_id');
        });
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->dropColumn('model_id');
        });
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->unsignedBigInteger('modele_id');
            $table->unsignedBigInteger('modele_compatible_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->dropColumn('modele_id');
        });
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->dropColumn('modele_compatible_id');
        });
        Schema::table('model_compatible', function (Blueprint $table) {
            $table->bigInteger('model_id');
            $table->bigInteger('model_compatible_id');
        });
    }
}
