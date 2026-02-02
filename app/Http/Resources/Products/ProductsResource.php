<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "category_id" => $this->category_id,
            "category_slug" => $this->category->translation?->slug,
            "code" => $this->code,

            'image' => $this->mainImage()
                ? asset('storage/' . $this->mainImage()->path)
                : null,


        ];
    }
}
