<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsSitemapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locales = ['am' => 'hy', 'ru' => 'ru', 'en' => 'en'];
        $translations = [];

        foreach ($locales as $front => $back) {
            $translation = $this->translation($back);
            $categoryTranslation = $this->category->translation($back);

            $translations[$front] = [
                'slug' => $translation?->slug ?? '',
                'category_slug' => $categoryTranslation?->slug ?? '',
            ];
        }

        return [
            'code' => $this->code,
            'updated_at' => $this->updated_at->format('Y-m-d'),
            'translations' => $translations,
        ];

    }
}
