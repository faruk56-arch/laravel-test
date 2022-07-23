<?php

namespace App\Jobs;

use App\Models\AlertSubscription;
use App\Models\Merchant;
use App\Services\SendinBlueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoulinetteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userAlerts = $this->getAlertSubscriptions();

        $sib = new SendinBlueService();

        $groupedAlerts = $userAlerts->get()->groupBy('merchant_id');

        /*
         * @var AlertSubscription $alert
         */
        foreach ($groupedAlerts as $key => $alerts) {
            $user = Merchant::findOrFail($key)->user()->first();
            $sib->recipient($user->email, $user->firstname.' '.$user->lastname)
                ->template(585)
                ->params(['alerts' => $alerts])
                ->send();
            // TODO : update dispatched for each alerts
        }
    }

    /**
     * @return \App\Models\AlertSubscription|\Illuminate\Database\Eloquent\Builder
     */
    public function getAlertSubscriptions()
    {
        $alerts = AlertSubscription::whereHas(
            'researches',
            function ($query) {
                $query->where('dispatched', 0);
            }
        )
            ->with(
                [
                    'merchant:id,user_id', 'merchant.user',
                    'researches',
                    'researches.part:id',
                    'category:id',
                    'modele:id,name,brand_id',
                    'part:id',
                ]
            );

        return $alerts;
    }
}
