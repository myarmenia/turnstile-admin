<?php

namespace App\Repositories\Products;

use App\Interfaces\Products\ProductInterface;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductRepository extends BaseRepository implements ProductInterface
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function getFeatured(): mixed
    {
        return $this->model->where('is_featured', true)->get();
    }

    public function findBySlug(string $slug): Model
    {

        return $this->model->whereHas('translations', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with('translations', 'attributeValues.translations', 'attributeValues.attribute.translations', 'images', 'category.translations')
            ->firstOrFail();
    }

    public function releatedProducts ($product_id, $category_id): Collection
    {
        $category = Category::findOrFail($category_id );
        $parent_id = $category->parent_id ?? $category->id; // если parent нет, берём саму категорию

        return $this->model
            ->whereHas('category', function($q) use ($parent_id) {
                $q->where('parent_id', $parent_id)
                ->orWhere('id', $parent_id);
            })
            ->where('id', '!=', $product_id)
            ->with(['category.translations', 'images'])
            ->get();

    }
}
