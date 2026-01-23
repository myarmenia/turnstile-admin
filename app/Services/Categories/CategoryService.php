<?php

namespace App\Services\Categories;

use App\Interfaces\Categories\CategoryInterface;
use App\Services\BaseService;

class CategoryService extends BaseService
{

    public function __construct( CategoryInterface $repository)
    {
        parent::__construct($repository);
    }


    public function children($slug){
        return $this->repository->getBySlug($slug)->children;
    }

    public function getChildrenBySlug(string $slug)
    {
        return $this->repository->getChildrenBySlug($slug);
    }




}
