<?php

namespace App\Http\Controllers\Actions;

use App\Actions\GetPublicModelsAction;
use App\Http\Controllers\Controller;

class PublicModelsController extends Controller
{
    public function __invoke(GetPublicModelsAction $actionModels)
    {
        return $actionModels->execute();
    }
}
