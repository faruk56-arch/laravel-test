<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id', 'order_items_ibfk_1')->references('id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('product_id', 'order_items_ibfk_2')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('order_items_ibfk_1');
                $table->dropForeign('order_items_ibfk_2');
            }
        });
    }
}
