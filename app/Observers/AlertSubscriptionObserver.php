<?php

namespace App\Observers;

use App\Jobs\AlertSubscriptionMatchResearchesJob;
use App\Models\AlertSubscription;
use App\Models\User;
use App\Notifications\Notif;
use DB;
use Notification;

class AlertSubscriptionObserver
{
    /**
     * Handle the alert subscription "created" event.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     *
     * @return void
     */
    public function created(AlertSubscription $alertSubscription)
    {
        $alertSubscription->load([
            'brand:id,name',
            'modele:id,name,year_start,year_end,img',
            'category:id',
            'part',
            'merchant.user',
        ]);
//        Notification::send(
//            $alertSubscription->merchant->user,
//            new Notif($alertSubscription, 'created')
//        );
        dispatch(new AlertSubscriptionMatchResearchesJob($alertSubscription));
    }

    /**
     * Handle the alert subscription "updated" event.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     *
     * @return void
     */
    public function updated(AlertSubscription $alertSubscription)
    {
        if ($alertSubscription->wasChanged('active')) {
            if ($alertSubscription->active) {
                dispatch(new AlertSubscriptionMatchResearchesJob($alertSubscription));
            } else {
                $alertSubscription->researches()->detach();
            }
        }
    }

    /**
     * Handle the alert subscription "deleted" event.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     *
     * @return void
     */
    public function deleted(AlertSubscription $alertSubscription)
    {
        DB::table('notifications')->whereJsonContains(
            'data',
            ['alert_subscriptions' => ['id' => $alertSubscription->id]]
        )->delete();
        $researches = $alertSubscription->researches()->withPivot('id')->get();
        foreach ($researches as $p) {
            \Illuminate\Support\Facades\DB::table('messages')->where('type', 'alert_subscription_researches')->
            whereIn('type_id', $p->pivot->id)->delete();
        }
    }

    /**
     * Handle the alert subscription "restored" event.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     *
     * @return void
     */
    public function restored(AlertSubscription $alertSubscription)
    {
        //
    }

    /**
     * Handle the alert subscription "force deleted" event.
     *
     * @param  \App\Models\AlertSubscription  $alertSubscription
     *
     * @return void
     */
    public function forceDeleted(AlertSubscription $alertSubscription)
    {
        //
    }
}
