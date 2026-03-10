<?php

namespace App\Models;

use App\Jobs\RefreshSitemap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryTranslation extends Model
{
    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {

        static::created(function ($category) {
            RefreshSitemap::dispatch('product_created');
        });

        static::updated(function ($category) {
            // Можно проверять, изменились ли важные поля
            if ($category->wasChanged(['slug'])) {
                RefreshSitemap::dispatch('product_updated');
            }
        });

        static::deleted(function ($category) {
            RefreshSitemap::dispatch('product_deleted');
        });
    }
}
