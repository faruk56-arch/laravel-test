<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('merchant_id');
            $table->integer('product_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('address');
            $table->integer('country_id');
            $table->string('region');
            $table->string('city');
            $table->string('email');
            $table->string('phone');
            $table->string('zip');
            $table->decimal('price');
            $table->decimal('platform_commission');
            $table->decimal('platform_earnings');
            $table->decimal('your_earnings');
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('merchant_id');
            $table->dropColumn('product_id');
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('address');
            $table->dropColumn('country_id');
            $table->dropColumn('region');
            $table->dropColumn('city');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('zip');
            $table->dropColumn('price');
            $table->dropColumn('platform_commission');
            $table->dropColumn('platform_earnings');
            $table->dropColumn('your_earnings');
        });
    }
}
