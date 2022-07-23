<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Research;
use App\Notifications\Notif;
use Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class MoulinetteMatchService
{
    public function research(Research $research)
    {
        $research->setAppends(['types']);
        $types = $research->types;
        $products = Product::where(
            [
                ['status', '<=', 2],
                ['sent', 1],
                ['stock', '>', 0],
            ]
        )->whereHas('merchant.user', function ($query) use ($research) {
            $query->where('id', '!=', $research->user->id);
        })
            ->where(
                function ($query) use ($research) {
                    $query->where('part_id', $research->part_id);
                    if ($research->reference !== null) {
                        $query->orWhere('reference', $research->reference);
                    }
                }
            )->where(function ($query) use ($research, $types) {
                if (! $research->type) {
                    $query->whereIn('type', $types);
                }
            })
            ->whereHas(
                'modele',
                function ($query) use ($research) {
                    $query->where('id', $research->modele->modele_id);
                }
            )
            ->get();

        $newProducts = collect($products)->diff($research->products);
        $research->products()->forceDelete();
        $research->products()->sync($products);
        if (count($products) > 0) {
            $research->load(['modele.modele.brand', 'part.category']);
            Notification::send(
                $research->user,
                $this->makeNotif($research, $newProducts)
            );
        }
    }

    public function product(Product $product)
    {
        $researches = Research::where(
            function ($query) use ($product) {
                $query->where('part_id', $product->part_id);
                if ($product->reference !== null) {
                    $query->orWhere('reference', $product->reference);
                }
            }
        )
            ->where('status', 'in_progress')
            ->whereNotIn('user_id', $product->merchant->user->pluck('id'))
            ->whereHas(
                'modele',
                function ($query) use ($product) {
                    $productModeles = $product->modele()->pluck('id')
                        ->toArray();
                    $query->whereIn('modele_id', $productModeles);
                }
            )->where(function ($query) use ($product) {
                $query->whereHas('types', function ($query) use ($product) {
                    $query->where('research_type.type_id', $product->type);
                });
                $query->orWhereNotNull('type');
            })
            ->get();
        $newProducts = collect($researches)->diff($product->research);
        $product->research()->forceDelete();
        $product->research()->sync($researches);
        if (count($researches) > 0) {
            foreach ($researches as $research) {
                //                Debugbar::info($research->toArray());
                $research->load(['modele.modele.brand', 'part.category']);
                Notification::send(
                    $research->user,
                    $this->makeNotif($research, $newProducts)
                );
            }
        }
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $research
     * @param  \Illuminate\Support\Collection  $newProducts
     *
     * @return \App\Notifications\Notif
     */
    public function makeNotif(
        Research $research,
        Collection $newProducts
    ): Notif {
        return new Notif(
            $research,
            'matched',
            623,
            [
                'count' => $newProducts->count(),
                'url'   => env('APP_URL').'/fr/finder/research/'
                    .$research->id,
            ]
        );
    }
}
