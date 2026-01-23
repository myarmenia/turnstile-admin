<?php

namespace App\Interfaces\Categories;

use App\Interfaces\BaseInterface;

interface CategoryInterface extends BaseInterface
{
    public function getChildrenBySlug(string $slug);
}
