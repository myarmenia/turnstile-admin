<?php

namespace App\Models;

use App\Jobs\RefreshSitemap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTranslation extends Model
{
    protected $guarded = [];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {

        static::created(function ($translation) {
            RefreshSitemap::dispatch('product_created');
        });

        static::updated(function ($translation) {
            // Можно проверять, изменились ли важные поля
            if ($translation->wasChanged(['slug'])) {
                RefreshSitemap::dispatch('product_updated');
            }
        });

        static::deleted(function ($translation) {
            RefreshSitemap::dispatch('product_deleted');
        });
    }
}
