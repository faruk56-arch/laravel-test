<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_subscription', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('merchant_id')->nullable();
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('modele_id')->nullable();
            $table->bigInteger('part_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_subscription');
    }
}
