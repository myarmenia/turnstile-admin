<?php

namespace App\Repositories;

use App\Interfaces\BaseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements BaseInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $with = []): Collection
    {
        return $this->model->with($with)->get();
    }


    public function getById(int $id, array $with = []): Model
    {
        return $this->model->with($with)->findOrFail($id);
    }

    public function getByParam(string $param, string $value, array $with = []): Model
    {
        return $this->model->with($with)->where($param, $value)->firstOrFail();
    }

    
    public function getBySlug(string $slug, array $with = []): Model
    {

        return $this->model->whereHas('translations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with($with)
            ->firstOrFail();
    }

    public function findBy(array $conditions, array $with = []): ?Model
    {
        return $this->model->with($with)->where($conditions)->first();
    }

    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->getById($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->getById($id)->delete();
    }

    public function getActiveRows(array $with = []): Collection
    {
        return $this->model->with($with)
            ->where('active', 1)
            ->get();
    }

    public function queryActiveRows(array $with = [])
    {
        return $this->model->with($with)
            ->where('active', 1);
    }

    public function getByFilter(array $conditions = [], array $with = []): Collection
    {
        return $this->model->with($with)
            ->where($conditions)
            ->get();
    }

    public function getMoreRows(int|string $field, array $conditions = [], array $with = []): Collection
    {
        return $this->model->with($with)
            ->whereIn($field, $conditions)
            ->get();
    }






}
