<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseInterface
{
    public function getAll(array $with = []): mixed;
    public function getById(int $id, array $with = []): Model;
    public function getBySlug(string $slug, array $with = []): Model;
    public function findBy(array $conditions, array $with = []): ?Model;
    public function store(array $data): Model;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getActiveRows(array $with = []): mixed;
    public function queryActiveRows(array $with = []);
    public function getByFilter(array $conditions = [], array $with = []): mixed;
    public function getMoreRows(int|string $field, array $conditions = [], array $with = []): mixed;


}
