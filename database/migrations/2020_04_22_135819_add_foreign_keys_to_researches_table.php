<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->unsignedBigInteger('part_id')->nullable();
            $table->unsignedBigInteger('modele_id')->nullable();
            $table->foreign('part_id')->references('id')->on('parts');
            $table->foreign('modele_id')->references('id')->on('users_modeles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('researches', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('researches_part_id_foreign');
                $table->dropForeign('researches_modele_id_foreign');
            }
        });
    }
}
