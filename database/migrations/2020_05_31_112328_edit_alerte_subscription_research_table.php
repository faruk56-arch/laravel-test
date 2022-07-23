<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditAlerteSubscriptionResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_subscription_research', function (Blueprint $table) {
            $table->renameColumn('subscription_id', 'alert_subscription_id');
        });
        Schema::table('alert_subscription_research', function (Blueprint $table) {
            $table->renameColumn('researach_id', 'research_id');
        });
        Schema::rename('alert_subscription', 'alert_subscriptions');
        Schema::rename('alert_subscription_research', 'alert_subscription_researches');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
