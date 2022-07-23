<?php

namespace App\Actions;

use App\Actions\AdminModifications\AdminEditable;

class TogglePublicAction
{

    public static function execute(AdminEditable $model)
    {
        $model->public = !$model->public;
        $model->saveWithoutEvents();
        return $model->public;
    }
}
