<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToAlertSubscriptionResearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_subscription_researches', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alert_subscription_researches', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
