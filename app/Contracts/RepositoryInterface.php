<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function find($id);

    public function all(): array;

    public function create(array $data): Model;

    public function update($id, array $data): Model;

    public function delete($id): void;

    public function findBy($field, $value, $columns = ['*']);
}
