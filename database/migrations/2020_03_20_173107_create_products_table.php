<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->string('name')->nullable();
            $table->integer('merchant_id')->nullable();
            $table->float('price', 10, 0)->nullable();
            $table->float('minPrice', 10, 0)->nullable();
            $table->float('shipping_cost', 10, 0)->nullable();
            $table->float('shipping_cost_abroad', 10, 0)->nullable();
            $table->string('currency')->nullable()->default('EUR');
            $table->string('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('suggestion')->nullable();
            $table->boolean('negotiable')->default(false);
            $table->integer('stock')->nullable();
            $table->integer('status')->nullable();
            $table->integer('condition')->nullable();
            $table->longtext('img')->nullable();
            $table->timestamps();
            $table->index(['merchant_id', 'status']);
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
        Schema::drop('products');
    }
}
