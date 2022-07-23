<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductNoDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('noDelivery', 'delivery_option');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->integer('delivery_option')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('delivery_option', 'noDelivery');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('noDelivery')->change();
        });
    }
}
