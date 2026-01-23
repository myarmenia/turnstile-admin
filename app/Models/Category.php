<?php

namespace App\Models;

use App\Filament\Traits\DynamicFilterTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, DynamicFilterTrait;

    protected $guarded = [];
    protected $appends = ['translation'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations?->firstWhere('locale', $locale);
    }

    public function getTranslationAttribute()
    {
        return $this->translations
            ? $this->translations->firstWhere('locale', app()->getLocale())
            : null;
    }



    public function getDepth(): int
    {
        $depth = 0;
        $parent = $this->parent;

        while ($parent instanceof self) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }
}
