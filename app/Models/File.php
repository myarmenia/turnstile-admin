<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'fileable');
    }

    public static function createFromPath(string $path, ?string $role = null): self
    {
        $disk = Storage::disk('public');
        $mime = $disk->mimeType($path);

        if (str_starts_with($mime, 'image')) {
            $type = 'image';
        } elseif (str_starts_with($mime, 'video')) {
            $type = 'video';
        } else {
            $type = 'document';
        }

        return self::create([
            'path' => $path,
            'type' => $type,
            'mime_type' => $mime,
            'size' => $disk->size($path),
        ]);
    }
}
