<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileTranslation extends Model
{
    protected $guarded = [];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
