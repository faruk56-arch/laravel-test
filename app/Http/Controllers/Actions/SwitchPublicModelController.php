<?php

namespace App\Http\Controllers\Actions;

use App\Actions\GetPublicModelsAction;
use App\Actions\TogglePublicAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Research;

class SwitchPublicModelController extends Controller
{
    public function product(Product $product)
    {
        return response()->json(TogglePublicAction::execute($product));
    }

    public function research(Research $research)
    {
        return response()->json(TogglePublicAction::execute($research));
    }
}
