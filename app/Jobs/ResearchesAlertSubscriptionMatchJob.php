<?php

namespace App\Jobs;

use App\Models\AlertSubscription;
use App\Models\Research;
use App\Notifications\Notif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;

class ResearchesAlertSubscriptionMatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Research
     */
    private $research;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Research  $research
     */
    public function __construct(Research $research)
    {
        $this->research = $research;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $research = $this->research;
        $alerts = AlertSubscription::where('active', 1)
                ->where('modele_id', $research->modele->modele_id)
                ->whereHas('merchant.user', function ($query) use ($research) {
                    $query->where('id', '!=', $this->research->user->id);
                })
                ->where('brand_id', $research->modele->modele->brand_id)
                ->where(function ($query) use ($research) {
                    $query->where('part_id', $research->part_id);
                    $query->orWhere('category_id', $research->part->category_id);
                })
                ->get();

        $research->alerts()->sync($alerts);
        if (count($alerts) > 0) {
            foreach ($alerts as $subscription) {
                foreach ($subscription->merchant->user as $user) {
                    $this->sendNotification($user, $subscription, $research);
                }
            }
        }
    }

    /**
     * @param $user
     * @param  AlertSubscription  $subscription
     * @param  \App\Models\Research  $research
     */
    public function sendNotification(
        $user,
        AlertSubscription $subscription,
        Research $research
    ): void {
        Notification::send(
            $user,
            new Notif($subscription->load(
                'brand',
                'modele',
            ), 'matched', 633, [
                'count'     => 1,
                'url'       => env('APP_URL')
                    .'/fr/finder/researchAlert/list/'
                    .$subscription->id,
                'url2' => env('APP_URL')
                    .'/fr/finder/researchAlert',
                'research'  => $research->load('part')->toArray(),
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
            ])
        );
    }
}
