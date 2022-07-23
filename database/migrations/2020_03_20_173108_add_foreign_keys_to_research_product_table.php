<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToResearchProductTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('research_product', function (Blueprint $table) {
            $table->foreign('research_id', 'research_product_ibfk_1')->references('id')->on('researches')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->foreign('product_id', 'research_product_ibfk_2')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('research_product', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('research_product_ibfk_1');
                $table->dropForeign('research_product_ibfk_2');
            }
        });
    }
}
