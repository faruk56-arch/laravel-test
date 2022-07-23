<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResearchesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->longText('img')->nullable();
            $table->text('details')->nullable();
            $table->enum('status', ['in_progress', 'finished', 'archived'])
                ->default(null)->nullable();
            $table->json('unknown_part')->nullable();
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
        Schema::drop('researches');
    }
}
