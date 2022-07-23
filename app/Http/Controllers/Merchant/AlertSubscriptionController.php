<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\AlertSubscription;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AlertSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Merchant  $merchant
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Merchant $merchant)
    {
        return response()->json(
            [
                'status' => 'success',
                'data'   => AlertSubscription::select(
                    'id',
                    'merchant_id',
                    'brand_id',
                    'modele_id',
                    'category_id',
                    'part_id',
                    'active'
                )
                    ->where('merchant_id', $merchant->id)
                    ->withCount('researches')->latest()->get(),
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Collection
     */
    public function store(Merchant $merchant, Request $request)
    {
        $subs = [];
        foreach ($request->models as $model) {
            foreach ($request->categories as $category) {
                $subscription = AlertSubscription::create(
                    [
                        'brand_id'    => $model[1],
                        'modele_id'   => $model[0],
                        'category_id' => $category,
                        'merchant_id' => $merchant->id,
                        'active'      => 1,
                    ]
                );
                $subs[] = $subscription->id;
            }
            foreach ($request->parts as $part) {
                $subscription = AlertSubscription::create(
                    [
                        'brand_id'    => $model[1],
                        'modele_id'   => $model[0],
                        'part_id'     => $part,
                        'merchant_id' => $merchant->id,
                        'active'      => 1,
                    ]
                );
                $subs[] = $subscription->id;
            }
        }

        return AlertSubscription::whereIn('id', $subs)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param    $alertSubscription
     *
     * @return \App\Models\AlertSubscription
     */
    public function show(
        Merchant $merchant,
        $id,
        Request $request
    ) {
        return AlertSubscription::findOrFail($id)->load('researches.modele');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Merchant  $merchant
     * @param  \App\Models\AlertSubscription  $researchAlert
     *
     * @return \App\Models\AlertSubscription
     */
    public function update(
        Request $request,
        Merchant $merchant,
        AlertSubscription $researchAlert
    ): AlertSubscription {
        if ($request->dismiss) {
            $researchAlert = $this->toggleDismissPivotValue(
                $researchAlert,
                $request
            );

            return $researchAlert;
        }
        $researchAlert->update($request->all());

        return $researchAlert;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AlertSubscription  $alertSubscription
     *
     * @return int
     */
    public function destroy(Merchant $merchant, $id)
    {
        return AlertSubscription::destroy($id);
    }

    /**
     * @param  \App\Models\AlertSubscription  $researchAlert
     * @param  \Illuminate\Http\Request  $request
     */
    private function toggleDismissPivotValue(
        AlertSubscription $researchAlert,
        Request $request
    ): AlertSubscription {
        $dismissValue
            = $researchAlert->researches->find($request->dismiss)->pivot->dismiss;
        if ($request->has('bool')) {
            $dismissValue = $request->bool;
        }
        $researchAlert->researches()
            ->updateExistingPivot(
                $request->dismiss,
                ['dismiss' => $dismissValue]
            );

        return AlertSubscription::find($researchAlert->id);
    }

    public function batchDelete(Request $request, Merchant $merchant)
    {
        $ids = $request->ids;
        AlertSubscription::whereIn('id', $ids)->where('merchant_id', $merchant->id)->delete();
    }
}
