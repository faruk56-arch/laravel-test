<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResearchProductTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_product', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->integer('research_id')->nullable()->index();
            $table->integer('product_id')->nullable()->index();
            $table->enum('status_preference', array('no','maybe','interested'))->nullable();
            $table->integer('status')->nullable();
            $table->text('status_note', 65535)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('research_product');
    }
}
