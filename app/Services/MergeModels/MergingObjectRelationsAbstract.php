<?php

namespace App\Services\MergeModels;

use Illuminate\Database\Eloquent\Model;

abstract class MergingObjectRelationsAbstract
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $oldModel;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $newModel;

    public function __construct(Model $oldModel, Model $newModel)
    {
        $this->oldModel = $oldModel;
        $this->newModel = $newModel;
    }

    //end __construct()

    /**
     * @throws \UnexpectedValueException
     */
    public function merge(): void
    {
        if ($this->oldModel->id === $this->newModel->id) {
            throw new \UnexpectedValueException();
        }

        foreach ($this->relationsWithAttribute() as $relation => $field) {
            $type = $this->oldModel->$relation();
            if ($this->isManyToMany($type)) {
                $this->manyToMany($relation, $field);
            } else {
                $this->standardRelation($relation, $field);
            }
        }

        $this->beforeDelete();

        $this->oldModel->forceDelete();
    }

    //end merge()

    abstract public function relationsWithAttribute(): array;

    public function beforeDelete()
    {
    }

    //end beforeDelete()

    private function manyToMany($relation, $field)
    {
        foreach ($this->oldModel->$relation as $item) {
            try {
                $this->oldModel->$relation()->withTrashed()->updateExistingPivot(
                    $item->id,
                    [$field => $this->newModel->id]
                );
            } catch (\BadMethodCallException $e) {
                $this->oldModel->$relation()->updateExistingPivot(
                    $item->id,
                    [$field => $this->newModel->id]
                );
            }
        }

        $this->oldModel->$relation()->sync([]);
    }

    //end manyToMany()

    private function standardRelation($relation, $field): void
    {
        $type = $this->oldModel->$relation();
        try {
            $type->withTrashed()->update([$field => $this->newModel->id]);
        } catch (\BadMethodCallException $e) {
            $type->update([$field => $this->newModel->id]);
        }
    }

    //end standardRelation()

    private function isManyToMany($type)
    {
        return get_class($type) === 'Illuminate\Database\Eloquent\Relations\BelongsToMany';
    }

    //end isManyToMany()
}//end class
