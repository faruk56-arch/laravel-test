<?php

namespace App\Http\Controllers\Actions;

use App\Actions\AdminModifications\ValidateModelAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\ValidateAdminChangesRequest;
use App\Models\Product;
use App\Models\Research;

class ValidateAdminChangesController extends Controller
{
    public function product(ValidateAdminChangesRequest $request, Product $product): void
    {
        if ($request->accept === true) {
            ValidateModelAction::accept($product);
            return;
        }
        ValidateModelAction::refuse($product);
    }

    public function research(ValidateAdminChangesRequest $request, Research $research): void
    {
        if ($request->accept === true) {
            ValidateModelAction::accept($research);
            return;
        }
        ValidateModelAction::refuse($research);
    }
}
