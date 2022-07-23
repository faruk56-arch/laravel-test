<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableValidations extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sender');
            $table->unsignedBigInteger('recipient')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('template_id');
            $table->json('params')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('sender')->references('id')->on('users');
            $table->foreign('recipient')->references('id')->on('users');
            $table->foreign('template_id')->references('id')
                ->on('message_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
