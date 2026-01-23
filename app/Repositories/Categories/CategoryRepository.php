<?php

namespace App\Repositories\Categories;

use App\Interfaces\Categories\CategoryInterface;
use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function __construct()
    {
        parent::__construct(new Category());
    }

    public function getChildrenBySlug(string $slug)
    {
        // Находим категорию по slug
        $category = $this->model->whereHas('translations', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->firstOrFail();

        // Если категория не родительская, берём её родителя
        if ($category->parent_id !== null) {
            $parent = $this->model->find($category->parent_id);
        } else {
            $parent = $category;
        }

        // Получаем всех детей родителя
        $children = $parent->children()->get(); // коллекция детей

        return $children;
    }



}
