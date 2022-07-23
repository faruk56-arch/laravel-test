<?php

namespace App\Actions\AdminModifications;

use App\Models\Product;
use App\Models\Research;
use App\Notifications\AdminModifications\AdminEditedProduct;
use App\Notifications\AdminModifications\AdminEditedResearch;
use Illuminate\Support\Collection;
use Notification;

class EditModelAction
{
    /**
     * @var \App\Actions\AdminModifications\AdminEditable
     */
    private $model;

    public function __construct(AdminEditable $model)
    {
        $this->model = $model;
    }

    public static function execute(AdminEditable $model, Collection $newValues): void
    {
        $obj = new self($model);
        $changedFields = $obj->filterUnchangedFields($newValues);
        if ($changedFields->isEmpty()) {
            return;
        }
        $obj->apply($changedFields);
        $obj->notify();
    }

    public function apply(Collection $newValues): void
    {
        $this->model->setMeta('modifications', $newValues);
    }

    public function filterUnchangedFields(Collection $newValues): Collection
    {
        $imgField = $this->compareArrayField('img', $newValues);
        $modelFields = $newValues->diffAssoc($this->model);

        return collect()->union($modelFields)->union($imgField);
    }

    public function notify(): void
    {
        if ($this->model instanceof Research) {
            Notification::send($this->model->user,
                new AdminEditedResearch($this->model));

            return;
        }
        if ($this->model instanceof Product) {
            Notification::send($this->model->merchant->user,
                new AdminEditedProduct($this->model));
        }
    }

    public function compareArrayField(string $field, Collection $newValues): ?Collection
    {
        if (! $newValues->has($field)) {
            return null;
        }
        $imgCollection = collect($newValues[$field]);
        $newValues->forget($field);

        $imgDiff = $imgCollection->diffAssoc($this->model->$field);
        if ($imgDiff->isEmpty()) {
            return null;
        }

        return collect([$field => $imgCollection->toArray()]);
    }
}
