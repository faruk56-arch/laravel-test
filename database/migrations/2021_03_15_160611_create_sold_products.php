<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldProducts extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id');
            $table->string('name')->nullable();
            $table->integer('merchant_id')->nullable();
            $table->double('price')->nullable();
            $table->double('minPrice')->nullable();
            $table->double('shipping_cost')->nullable();
            $table->double('shipping_cost_abroad')->nullable();
            $table->string('currency')->nullable()->default('EUR');
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('suggestion')->nullable();
            $table->boolean('negotiable')->default(0);
            $table->integer('stock')->nullable();
            $table->integer('status')->nullable();
            $table->string('reference')->nullable();
            $table->integer('condition')->nullable();
            $table->text('img')->nullable();
            $table->unsignedBigInteger('part_id')->nullable();
            $table->string('type')->nullable();
            $table->boolean('noDelivery')->nullable();
            $table->double('weight')->nullable();
            $table->boolean('sent');
            $table->string('intern')->nullable();
            $table->integer('average_preparation_time')->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('depth')->nullable();
            $table->integer('research_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('sold_products', function (Blueprint $table) {
            $table->foreign('research_id')->references('id')
                ->on('researches');
        });
        Schema::create('research_sold_product', function (Blueprint $table) {
            $table->unsignedBigInteger('sold_product_id');
            $table->integer('research_id');
            $table->foreign('sold_product_id')->references('id')
                ->on('sold_products');
            $table->foreign('research_id')->references('id')->on('researches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sold_product_research');
        Schema::dropIfExists('sold_products');
    }
}
