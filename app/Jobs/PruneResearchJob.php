<?php

namespace App\Jobs;

use App\Models\Research;
use App\Notifications\ResearchArchived;
use Carbon\Carbon;
use Crypt;
use Hashids\Hashids;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Notification;

class PruneResearchJob implements ShouldQueue
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
        //on prend les intervalles d'un jour pour éviter les envois de mails en double
        $firstCallEnd = Carbon::now()->subMonths(3)->addDays(11);
        $firstCall = Carbon::now()->subMonths(3)->addDays(10);

        $lastCallEnd = Carbon::now()->subMonths(3)->addDays(4);
        $lastCall = Carbon::now()->subMonths(3)->addDays(3); //

        $threeMonthAgoEnd = Carbon::now()->subMonths(3)->addDay();
        $threeMonthAgo = Carbon::now()->subMonths(3);

        $researchesToNotify10 = Research::with('user', 'part')
            ->where('status', 'in_progress')->where('created_at', '<=', $firstCallEnd)
            ->where('created_at', '>', $firstCall)
            ->where('still', 0)
            ->get();

        $researchesToNotify3 = Research::with('user', 'part')
            ->where('status', 'in_progress')->where('created_at', '<=', $lastCallEnd)
            ->where('created_at', '>', $lastCall)
            ->where('still', 0)
            ->get();

        $queryToArchive = Research::with('user', 'part')->select('id')//on archive les recherches non validées comme toujours en cours
        ->where('status', 'in_progress')->where('created_at', '<=', $threeMonthAgoEnd)
            ->where('created_at', '>', $threeMonthAgo)
            ->where('still', 0);
        $queryToArchive->update(['status'=>'archived']);
        $researchesArchived = $queryToArchive->get();

        foreach ($researchesToNotify10 as $research) {//on notifie les utilisateurs avec des vieilles recherches 10j avant l'archivage auto
            if ($research->user) {
                $encryptedId = $this->encryptString($research->id);
                Notification::send(
                    $research->user,
                    new ResearchArchived(
                        $research,
                        665,
                        ['url'       => env('APP_URL').'/fr/research/still/'.$encryptedId]
                    )
                );
            }
        }
        foreach ($researchesToNotify3 as $research) {//on notifie les utilisateurs avec des vieilles recherches 3j avant l'archivage auto
            if ($research->user) {
                $encryptedId = $this->encryptString($research->id);
                Notification::send(
                    $research->user,
                    new ResearchArchived(
                        $research,
                        795,
                        ['url'       => env('APP_URL').'/fr/research/still/'.$encryptedId]
                    )
                );
            }
        }

        foreach ($researchesArchived as $research) {//on notifie les utilisateurs avec des vieilles recherches archivée pour les prévenir
            if ($research->user) {
                $encryptedId = $this->encryptString($research->id);
                Notification::send(
                    $research->user,
                    new ResearchArchived(
                        $research,
                        796,
                        ['url'       => env('APP_URL').'/fr/research/still/'.$encryptedId]
                    )
                );
            }
        }
    }

    public function encryptString(int $id): string
    {
        return (new Hashids(config('app.key'), 10))->encode($id);
    }
}
