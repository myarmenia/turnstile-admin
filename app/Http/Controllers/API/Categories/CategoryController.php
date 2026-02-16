<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Categories\CategoriesResource;
use App\Services\Categories\CategoryService;

class CategoryController extends BaseController
{
    public function __construct(protected CategoryService $service) {}

    public function index()
    {
        $categories = $this->service->getActiveRows();

        return $this->sendResponse(
            CategoriesResource::collection($categories),
            'Category retrieved successfully'
        );
    }
}
