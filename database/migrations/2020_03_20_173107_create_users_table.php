<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('zip')->nullable();
            $table->integer('merchant_id')->nullable();
            $table->integer('merchant_role')->nullable();
            $table->string('email')->unique();
            $table->string('obvy_email')->nullable();
            $table->string('obvy_pseudo')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
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
        Schema::drop('users');
    }
}
