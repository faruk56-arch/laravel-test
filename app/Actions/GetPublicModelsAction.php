<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Research;

class GetPublicModelsAction
{
    public function execute(): array
    {
        $publicResearches = Research::select(['id', 'status', 'public', 'part_id', 'modele_id'])
            ->where('public', true)->where('status', 'in_progress')
            ->with('modele.modele')->withCount('products')->inRandomOrder()->get()->take(10);
        $publicProducts = Product::select(['id', 'name', 'public', 'status', 'sent', 'description', 'img', 'part_id'])
            ->with('modele')->where('public', true)->where('status', 1)
            ->where('sent', 1)->inRandomOrder()->get()->take(10);

        return [
            $publicResearches,
            $publicProducts,
        ];
    }
}
