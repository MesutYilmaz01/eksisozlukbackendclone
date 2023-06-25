<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IEntryRepository;
use App\Models\Entry;

class EntryRepository extends BaseEloquentRepository implements IEntryRepository
{
    protected $model = Entry::class;

    public function deleteById(int $id)
    {
        return $this->model->query()->where('id', $id)->delete();
    }

    public function updateById(int $id, array $data)
    {
        return $this->model->query()->where('id', $id)->update($data);
    }
}

