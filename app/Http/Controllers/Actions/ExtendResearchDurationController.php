<?php

namespace App\Http\Controllers\Actions;

use App\Actions\ExtendResearchDurationAction;
use App\Http\Controllers\Controller;

class ExtendResearchDurationController extends Controller
{
    public function __invoke(string $encryptedId, ExtendResearchDurationAction $durationAction)
    {
        $durationAction->execute($encryptedId);
    }
}
