<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsResource;
use App\Services\Products\ProductService;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function __construct(protected ProductService $service) {}

    public function index()
    {
        $products = $this->service->getActiveRows();

        return $this->sendResponse(
            ProductsResource::collection($products),
            'Products retrieved successfully'
        );
    }


    public function show(Request $request, string $id)
    {

        try {
            // $product = $this->service->getById($id, $request->header('Accept-Language', 'ru'));
            $product = $this->service->getById($id);

            return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendError( $e->getMessage(), [], $e->getCode() ?: 400);
        }
    }


    public function showByCode(string $code)
    {

        try {
            // $product = $this->service->getById($id, $request->header('Accept-Language', 'ru'));
            $product = $this->service->getByParam($code);

            return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], $e->getCode() ?: 400);
        }
    }
}
