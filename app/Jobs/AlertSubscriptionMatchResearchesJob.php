<?php

namespace App\Jobs;

use App\Models\AlertSubscription;
use App\Models\Research;
use App\Models\User;
use App\Notifications\Notif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;

class AlertSubscriptionMatchResearchesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\AlertSubscription
     */
    private $subscription;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\AlertSubscription  $subscription
     */
    public function __construct(AlertSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscription = $this->subscription;
        $researches = Research::where(
            function ($query) use ($subscription) {
                $query->whereHas(
                    'modele.modele',
                    function ($query) use ($subscription) {
                        $query->where('id', $subscription->modele_id);
                        $query->where('brand_id', $subscription->brand_id);
                    }
                );
                if ((int) $subscription->part_id !== 0
                    && $subscription->part_id !== null
                ) {
                    $query->where('part_id', $subscription->part_id);
                } else {
                    $query->whereHas(
                        'part',
                        function ($query) use ($subscription) {
                            $query->where(
                                'category_id',
                                $subscription->category_id
                            );
                        }
                    );
                }
            }
        )
            ->where('status', 'in_progress')
            ->whereNotIn('user_id', $subscription->merchant->user->pluck('id'))
            ->get();
        $newProducts
                      = collect($researches)->diff($subscription->researches);
        $subscription->researches()->sync($researches);
        if (count($researches) > 0) {
            foreach ($subscription->merchant->user as $user) {
                $this->sendNotification($user, $subscription, $newProducts);
            }
        }
    }

    /**
     * @param  \App\Models\User  $user
     * @param  \App\Models\AlertSubscription  $subscription
     * @param  \Illuminate\Support\Collection  $newProducts
     */
    public function sendNotification(
        User $user,
        AlertSubscription $subscription,
        \Illuminate\Support\Collection $newProducts
    ): void {
        if ($newProducts->count() === 1) {
            $research_id = $newProducts->first()->id;
            $research = Research::find($research_id)->load('part');
        }

        Notification::send(
            $user,
            new Notif(
                $subscription->load(
                    'brand',
                    'modele',
                    'part'
                ),
                'matched',
                633,
                [
                    'count'     => $newProducts->count(),
                    'url'       => env('APP_URL')
                        .'/fr/finder/researchAlert/list/'
                        .$subscription->id,
                    'url2'      => env('APP_URL')
                        .'/fr/finder/researchAlert',
                    'research' => $newProducts->count() === 1 ? $research->toArray() : null,
                    'firstname' => $user->firstname,
                    'lastname'  => $user->lastname,
                ]
            )
        );
    }
}
