<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTranslationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->string('language')->nullable()->default('fr-FR');
            $table->string('type')->nullable()->comment('nom de la table concernée');
            $table->integer('type_id')->nullable()->comment('id de l\'element');
            $table->string('key')->nullable();
            $table->string('fr_FR')->nullable();
            $table->string('en_EN')->nullable();
            $table->string('de_DE')->nullable();
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
        Schema::drop('translations');
    }
}
