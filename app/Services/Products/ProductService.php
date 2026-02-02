<?php

namespace App\Services\Products;

use App\Interfaces\Products\ProductInterface;
use App\Models\Category;
use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService extends BaseService
{

    public function __construct( ProductInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getById(int $id, array $with = []): mixed
    {
        $with = ['translations'];
        $lang = request()->header('Accept-Language', 'ru') ?? 'hy';

        try {
            $product = $this->repository->getById($id, $with);


            $translation = $product->translations->firstWhere('lang', $lang)
                ?? $product->translations->first();

            $product->current_translation = $translation;

            return $product;
        } catch (ModelNotFoundException $e) {
                abort(404, 'Product not found');
        }
    }


    public function getBySlug(string $slug, string $lang = 'ru'): Product
    {

        $product = $this->repository->getBySlug($slug);

        if (!$product->active()) {
            throw new \Exception('Product not active', 400);
        }

        $translation = $product->translations->firstWhere('lang', $lang)
            ?? $product->translations->first();

        $product->current_translation = $translation;

        return $product;
    }

    public function getByParam(string $code): Product
    {
        $lang = request()->header('Accept-Language', 'ru') ?? 'hy';

        $product = $this->repository->getByParam('code', $code);

        if (!$product->active()) {
            throw new \Exception('Product not active', 400);
        }

        $translation = $product->translations->firstWhere('lang', $lang)
            ?? $product->translations->first();

        $product->current_translation = $translation;

        return $product;
    }




    public function releatedProducts ($product_id, $category_id): mixed
    {
        $products = $this->repository->releatedProducts($product_id, $category_id);

        return $products;
    }


// ProductService.php
    public function getFilteredProducts(array $filters = [], int $perPage = 9, ?string $category_slug = null)
    {

        $query = $this->repository->queryActiveRows([
            'category.translations',
            'images',
            'attributeValues.attribute'
        ]);

        // if (!empty($category_slug)) {
        //     $query->whereHas('category.translations', function ($q) use ($category_slug) {
        //         $q->where('slug', $category_slug);
        //     });
        // }

        $categories = [];

        // Сначала смотрим, есть ли выбранные категории из фильтра
        if (!empty($filters['categories'])) {
            $categories = $filters['categories'];
        }
        //Если фильтр пустой, но есть category_slug из route
        elseif (!empty($category_slug)) {
            $category = Category::whereHas('translations', function($q) use ($category_slug) {
                $q->where('slug', $category_slug);
            })->first();

            if ($category) {
                if ($category->parent_id === null) {
                    // Главная категория: включаем всех детей + саму категорию
                    $categories = array_merge([$category->id], $category->children()->pluck('id')->toArray());
                } else {
                    // Просто конкретная категория
                    $categories = [$category->id];
                }
            }
        }

        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        // if (!empty($filters['attributes'])) {
        //     $query->whereHas('attributeValues', function($q) use ($filters) {
        //         $q->whereIn('attribute_value_id', $filters['attributes']);
        //     });
        // }

        if (isset($filters['price_min']) && isset($filters['price_max'])) {
            $min = $filters['price_min'];
            $max =  $filters['price_max'];

            $query->whereBetween('price', [$min, $max]);
        }


        if (!empty($filters['attributes'])) {
            $attributeValueIds = $filters['attributes'];

            $query->whereHas('attributeValues', function ($q) use ($attributeValueIds) {
                $q->whereIn('attribute_values.id', $attributeValueIds);
            }, '=', count($attributeValueIds));
        }


        return $query->paginate($perPage);
    }

}
