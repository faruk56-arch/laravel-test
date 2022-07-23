<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeResearchNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sold_products', function (Blueprint $table) {
            $table->dropForeign('sold_products_research_id_foreign');
        });
        Schema::table('sold_products', function (Blueprint $table) {
            $table->integer('research_id')->nullable()->change();
        });
        Schema::table('sold_products', function (Blueprint $table) {
            $table->foreign('research_id')->references('id')
                ->on('researches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_products', function (Blueprint $table) {
            $table->dropForeign('sold_products_research_id_foreign');
        });
        Schema::table('sold_products', function (Blueprint $table) {
            $table->integer('research_id')->nullable(false)->change();
        });
        Schema::table('sold_products', function (Blueprint $table) {
            $table->foreign('research_id')->references('id')
                ->on('researches');
        });
    }
}
