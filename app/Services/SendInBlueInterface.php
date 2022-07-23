<?php

namespace App\Services;

use App\Models\User;
use App\Services\MergeModels\SendInBlueData;

interface SendInBlueInterface
{
    public function toSendInBlue(User $notifiable): SendInBlueData;
}
