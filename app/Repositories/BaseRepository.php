<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Mockery\CountValidator\Exception;

/**
 * Class Repository.
 * @author Thomas Tosch <thomas.tosch@wigo-media.com>
 * Provides a repository base class to extend delivered with crud functions
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    /**
     * Must return model class.
     * @return Model
     */
    abstract public function model();

    /**
     * Creates an instance of the extended model.
     * @return Model
     */
    public function makeModel()
    {
        /** @var Model $model */
        $model = App::make($this->model());

        if (! $model instanceof Model) {
            throw new Exception;
        }

        return $model;
    }

    /**
     * Gets every model.
     * @return array
     */
    public function all(): array
    {
        return $this->model->all();
    }

    /**
     * Find models with their $id (can be array or int).
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a model form the given $data.
     * @param  array  $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Updates a model by it's $id with the given $data.
     * @param $id
     * @param  array  $data
     * @return Model
     */
    public function update($id, array $data): Model
    {
        $model = $this->model->where('id', $id);
        $model->update($data);

        return $model;
    }

    /**
     * Removes a modle by it's $id.
     * @param $id
     */
    public function delete($id): void
    {
        $this->model->destroy($id);
    }

    /**
     * Finds every model where $field has $value and returns columns specified in $columns.
     * @param $field
     * @param $value
     * @param  array  $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field, $value)->get($columns);
    }

    public function latest()
    {
        return $this->model->all()->last();
    }
}
