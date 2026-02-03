<?php

namespace App\Models;

use App\Filament\Traits\DynamicFilterTrait;
use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFiles, DynamicFilterTrait;

    protected $guarded = [];
    // public $appends = ['name'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }


    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->firstWhere('lang', $locale);
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->translations
            ->where('locale', app()->getLocale())
            ->first()
            ?->name;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
