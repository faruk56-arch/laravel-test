<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\ReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;

class MonthlyReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $users= User::whereHas('models')->get();
        $users = User::where('id', 1)->get();
        foreach ($users as $i => $user) {
            $products = Product::whereHas('modele.users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 1)->where('sent', 1)->take(10)->get();
            Notification::send(
                $user,
                new ReminderNotification(
                    $products,
                    626,
                    $user,
                    ['url' => env('APP_URL').'/fr/model']
                )
            );
        }
    }
}
