<?php

namespace App\Actions;

use App\Models\Research;
use Crypt;
use Hashids\Hashids;

class ExtendResearchDurationAction
{
    public function execute(string $encryptedId): void
    {
        $researchId = $this->decryptId($encryptedId);
        $research = Research::whereStill(0)->where('status', '!=', 'finished')->findOrFail($researchId);
        $research->still = 1;
        $research->status = 'in_progress';
        $research->save();
    }

    public function decryptId(string $encryptedId): int
    {
        $decrypt = (new Hashids(config('app.key'), 10))
            ->decode($encryptedId);
        if (sizeof($decrypt) === 1) {
            return (int)$decrypt[0];
        }
        return (int) Crypt::decryptString($encryptedId);
    }
}
