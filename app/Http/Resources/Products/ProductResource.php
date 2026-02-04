<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = request()->header('Accept-Language', 'ru') ?? 'hy';
        $translation = $this->current_translation ?? null;
        $category = $this->category?->translation($lang);

        // $categorySlug = $this->category?->translation($lang)?->slug;

        return [
            'id' => $this->id,
            'slug' => $translation?->slug,
            'code' => $this->code,
            'name' => $translation?->name,
            'description' => $translation?->description,
            'specifications' => $translation?->specifications,
            'category_slug' => $category?->slug,
            'category_name' => $category?->name,

            // ===== FILES =====

            'main_image' => $this->getMainImageForApi($lang),

            'slider_images' => $this->getFilesByRoleForApi('slider', $lang),

            'additional_files' => $this->getFilesByRoleForApi('additional', $lang),

            'videos' => $this->getFilesByRoleForApi('video', $lang),

            'documents' => $this->getFilesByRoleForApi('document', $lang),

            // 'main_image' => $this->mainImage()
            //     ? asset('storage/' . $this->mainImage()->path)
            //     : null,

            // 'slider_images' => $this->sliderImages()
            //     ->map(fn($file) => asset('storage/' . $file->path))
            //     ->toArray(),

            // 'additional_files' => $this->additionalFiles()
            //     ->map(fn($file) => asset('storage/' . $file->path))
            //     ->toArray(),

            // 'videos' => $this->videos()
            //     ->map(fn($file) => asset('storage/' . $file->path))
            //     ->toArray(),

            // 'documents' => $this->documents()
            //     ->map(fn($file) => asset('storage/' . $file->path))
            //     ->toArray(),
        ];
    }


}
