<?php

namespace App\Http\Controllers\Actions;

use App\Actions\AdminModifications\EditModelAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Research;
use Illuminate\Http\Request;

class AdminEditModelController extends Controller
{
    public function product(Request $request, Product $product): void
    {
        EditModelAction::execute($product, collect($request->all()));
    }

    public function research(Request $request, Research $research): void
    {
        EditModelAction::execute($research, collect($request->all()));
    }
}
