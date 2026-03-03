<?php

namespace App\Interfaces\Categories;

use App\Interfaces\BaseInterface;

interface CategoryInterface extends BaseInterface
{
    public function getChildrenBySlug(string $slug);
    public function getActiveCategoriesWithProducts(array $with = []): mixed;
}
