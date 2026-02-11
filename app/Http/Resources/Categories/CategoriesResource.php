<?php

namespace App\Http\Resources\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = request()->header('Accept-Language', 'ru') ?? 'hy';
        $translation = $this->translation($lang) ?? null;

        return [
            "id" => $this->id,
            "slug" => $translation->slug,
            "name" => $this->translation($lang)->name
        ];
    }
}
