<?php

namespace App\Observers;

use App\Events\ResearchFinishedEvent;
use App\Jobs\NewResearchJob;
use App\Jobs\ResearchesAlertSubscriptionMatchJob;
use App\Models\Research;
use App\Services\MoulinetteMatchService;
use Illuminate\Support\Facades\DB;
use Storage;

class ResearchObserver
{
    /**
     * Handle the research "created" event.
     *
     * @return void
     */
    public function created(Research $research)
    {
//        dispatch(new NewResearchJob($research));
    }

    /**
     * Handle the research "updated" event.
     *
     * @param  Research  $research
     *
     * @return void
     */
    public function updated(Research $research)
    {
        if ($research->wasChanged('status')) {
            if ($research->status === 'finished') {
                event(new ResearchFinishedEvent($research));
            }
        }
        if (! $research->wasChanged('still') && $research->status === 'in_progress') {
            $research->load(['modele.modele.brand', 'part.category']);
            $service = new MoulinetteMatchService();
            $service->research($research);
            dispatch(new ResearchesAlertSubscriptionMatchJob($research));
        }
        //        if ($research->status=='archived') {
        //            event(new ResearchArchivedEvent($research));
        //        }
    }

    /**
     * Handle the research "deleted" event.
     *
     * @return void
     */
    public function deleted(Research $research)
    {
//        if ($research->img) {
//            foreach ($research->img as $image) {
//                $this->removeImageFromDisk($image);
//            }
//        }
        DB::table('notifications')->whereJsonContains(
            'data',
            ['researches' => ['id' => $research->id]]
        )->delete();
        $produits = $research->products()->withPivot('id')->get();
        foreach ($produits as $p) {
            DB::table('messages')->where('type', 'research_product')->
            whereIn('type_id', [$p->pivot->id])->delete();
        }
        $research->alerts->each(function ($alert, $key) {
            $alert->delete();
        });
        $research->products()->detach();
    }

    /**
     * Handle the research "restored" event.
     *
     * @param  \App\Research  $research
     *
     * @return void
     */
    public function restored(Research $research)
    {
        //
    }

    /**
     * Handle the research "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Research $research)
    {
        //
    }

    /**
     * @param $image
     */
    private function removeImageFromDisk($image): void
    {
        $image = str_replace('/storage', 'public', $image);
        Storage::delete($image);
    }
}
