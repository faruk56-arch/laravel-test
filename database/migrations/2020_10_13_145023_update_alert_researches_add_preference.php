<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAlertResearchesAddPreference extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'alert_subscription_researches',
            function (Blueprint $table) {
                $table->boolean('dismiss')->default(false);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'alert_subscription_researches',
            function (Blueprint $table) {
                $table->removeColumn('dismiss');
            }
        );
    }
}
