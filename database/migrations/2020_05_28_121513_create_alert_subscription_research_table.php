<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertSubscriptionResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_subscription_research', function (Blueprint $table) {
            $table->bigInteger('researach_id')->nullable();
            $table->bigInteger('subscription_id')->nullable();
            $table->bigInteger('dispatched')->nullable();
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
        Schema::dropIfExists('alert_subscription_research');
    }
}
