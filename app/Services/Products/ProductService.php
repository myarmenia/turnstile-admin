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



    public function getFilteredProducts($request, int $perPage = 9)
    {

        $category_id = $request->category_id ?? null;
        $product_code = $request->code ?? null;

        $query = $this->repository->queryActiveRows([
            'category.translations'
        ]);

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($product_code)) {
            $query->where('code', $product_code);
        }


        return $query->paginate($perPage);
    }

}
