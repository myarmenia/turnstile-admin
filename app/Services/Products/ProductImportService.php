<?php

namespace App\Services\Products;

use App\Clients\GoogleSheetsClient;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImportService
{
    protected GoogleSheetsClient $client;

    public function __construct(GoogleSheetsClient $client)
    {
        $this->client = $client;
    }

    public function importFromSheet(string $sheetId, string $range): void
    {
        $rows = $this->client->getAssocRows($sheetId, $range);

        foreach ($rows as $item) {
            $category = Category::whereHas('translations', function($q) use ($item) {
                $q->where('slug', $item['sub_category_slug']);
            })->first();

            $product = Product::updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $category?->id,
                    'price' => $item['price'] ?? 0,
                    'seria' => $item['seria'] ?? null,
                    'active' => $item['active'] ?? 1,
                ]
            );

            $product->stock()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $item['stock_quantity'] ?? 0]
            );

            // Переводы
            foreach (['hy', 'ru', 'en'] as $locale) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $item["{$locale}_name"] ?? '',
                        'slug' => $item["{$locale}_slug"] ?? Str::slug($item["{$locale}_name"] ?? ''),
                        'description' => $item["{$locale}_description"] ?? '',
                    ]
                );
            }

            // Атрибуты
            $attributes = [
                'color',
                'motor_power_kw',
                'motor_power_hp',
                'supply_voltage',
                'current_rating',
                'phases',
                'supply_frequency',
                'communication_protocol',
                'cooling_type',
                'ip_rating',
                'width_mm',
                'height_mm',
                'depth_mm',
            ];

            foreach ($attributes as $attr) {
                if (!empty($item[$attr])) {
                    $attribute = Attribute::where('slug', $attr)->first();
                    $value = AttributeValue::where('attribute_id', $attribute?->id)
                        ->where('code', $item[$attr])
                        ->first();

                    if ($attribute && $value) {
                        $product->attributeValues()->syncWithoutDetaching([$value->id]);
                    }
                }
            }

            // Картинки
            // if (!empty($item['image_url'])) {
            //     try {
            //         $product->addMediaFromUrl($item['image_url'])
            //             ->toMediaCollection('images');
            //     } catch (\Exception $e) {
            //         // Логируем ошибки скачивания картинок
            //         logger()->error("Ошибка добавления картинки для SKU {$product->sku}: {$e->getMessage()}");
            //     }
            // }

            if (!empty($item['image_url'])) {
                try {
                    $this->saveProductImage($product, $item['image_url'], true);
                } catch (\Throwable $e) {
                    logger()->error('Image import failed', [
                        'sku' => $product->sku,
                        'url' => $item['image_url'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }


    private function normalizeGoogleDriveUrl(string $url): string
    {
        $url = trim($url);

        if (str_contains($url, 'drive.google.com')) {
            if (preg_match('~/file/d/([^/]+)~', $url, $m)) {
                return 'https://drive.google.com/uc?export=download&id=' . $m[1];
            }
        }

        return $url;
    }

    private function saveProductImage(Product $product, string $url, bool $isMain = false): void
    {
        $url = $this->normalizeGoogleDriveUrl($url);

        $response = Http::timeout(30)->get($url);

        if (! $response->successful()) {
            throw new \Exception('Image download failed');
        }

        $extension = 'jpg';
        $contentType = $response->header('Content-Type');

        if (str_contains($contentType, 'png')) {
            $extension = 'png';
        } elseif (str_contains($contentType, 'webp')) {
            $extension = 'webp';
        }

        $fileName = Str::uuid() . '.' . $extension;
        $path = "products/{$product->id}/{$fileName}";

        Storage::disk('public')->put($path, $response->body());

        $product->images()->create([
            'path' => $path,
            'is_main' => $isMain,
        ]);
    }

}
