<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Research;
use App\Notifications\ResearchCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;

class NewResearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $research;

    /**
     * Create a new job instance.
     *
     * @param Research $research
     */
    public function __construct(Research $research)
    {
        $this->research = $research->load('user');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $researchPart = $this->research->part_id;
        $products = Product::whereHas('modele', function ($query) {
            $query->where('id', $this->research->modele->modele->id);
        })->where('status', 1)->where('sent', 1)->where('part_id', '!=', $researchPart)->take(10)->get();
        if ($products->count() > 0) {
            Notification::send(
                $this->research->user,
                new ResearchCreatedNotification(
                    $products,
                    630,
                    $this->research,
                    ['url' => env('APP_URL').'/fr/model']
                )
            );
        }
    }
}
