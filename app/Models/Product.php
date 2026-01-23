<?php

namespace App\Models;

use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFiles;

    protected $guarded = [];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    
}
