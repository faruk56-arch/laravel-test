<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMerchantsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->string('merchant_name')->nullable();
            $table->string('merchant_address')->nullable();
            $table->string('merchant_city')->nullable();
            $table->string('merchant_zip')->nullable();
            $table->string('merchant_siret')->nullable();
            $table->string('merchant_type')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('currency')->nullable();
            $table->string('created at')->nullable();
            $table->string('stripe_connect_id')->nullable();
            $table->boolean('completed_stripe_onboarding')->default(false);
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
        Schema::drop('merchants');
    }
}
