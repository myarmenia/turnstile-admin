<?php

namespace App\Services;

use App\Interfaces\BaseInterface;

abstract class BaseService
{

    public function __construct(protected BaseInterface $repository)
    {

    }

    public function getAll(array $with = []): mixed
    {
        return $this->repository->getAll($with);
    }

    public function getById(int $id, array $with = []): mixed
    {
        return $this->repository->getById($id, $with);
    }

    public function store(array $data): mixed
    {
        return $this->repository->store($data);
    }

    public function update(int $id, array $data): mixed
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): mixed
    {
        return $this->repository->delete($id);
    }

    public function getActiveRows(array $with = []): mixed
    {
        return $this->repository->getActiveRows($with);
    }


    public function queryActiveRows(array $with = []): mixed
    {
        return $this->repository->queryActiveRows($with);
    }


    public function getByFilter(array $conditions = [], array $with = []): mixed
    {
        return $this->repository->getByFilter($with);
    }

    public function getMoreRows(int|string $field, array $conditions = [], array $with = []): mixed
    {
        return $this->repository->getMoreRows($field, $conditions, $with);
    }
}
