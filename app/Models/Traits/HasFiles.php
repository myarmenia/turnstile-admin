<?php
namespace App\Models\Traits;

use App\Models\File;

trait HasFiles
{
    // public function files()
    // {
    //     return $this->morphToMany(File::class, 'fileable')
    //         ->withPivot(['role', 'sort_order']);
    // }

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withPivot(['role', 'sort_order'])
            ->orderBy('fileables.sort_order');
    }

    // ===== GETTERS =====

    public function mainImage()
    {
        return $this->files()->wherePivot('role', 'main')->first();
    }

    public function mainImagePath(): ?string
    {
        return $this->mainImage()?->path;
    }

    public function sliderImages()
    {
        return $this->files()
            ->wherePivot('role', 'slider')
            ->orderBy('pivot_sort_order')
            ->get();
    }


    public function additionalFiles()
    {
        // здесь могут быть картинки, видео и документы
        return $this->files()
            ->wherePivot('role', 'additional')
            ->orderBy('pivot_sort_order')
            ->get();
    }

    public function videos()
    {
        return $this->files()->wherePivot('role', 'video')->get();
    }

    public function documents()
    {
        return $this->files()->wherePivot('role', 'document')->get();
    }

    // public function addFiles(array $paths, string $role): void
    // {
    //     $currentCount = $this->files()->wherePivot('role', $role)->count();

    //     foreach ($paths as $index => $path) {
    //         $file = File::createFromPath($path);

    //         if (!$this->files()->where('files.id', $file->id)->wherePivot('role', $role)->exists()) {
    //             $this->files()->attach($file->id, [
    //                 'role' => $role,
    //                 'sort_order' => $currentCount + $index,
    //             ]);
    //         }
    //     }
    // }

    public function addFiles(array $paths, string $role): void
    {
        $currentCount = $this->files()
            ->where('fileables.role', $role)
            ->count();

        foreach ($paths as $index => $path) {
            $file = File::createFromPath($path);

            $exists = $this->files()
                ->where('files.id', $file->id)
                ->where('fileables.role', $role)
                ->exists();

            if (! $exists) {
                $this->files()->attach($file->id, [
                    'role'       => $role,
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }
    }



    public function syncSingleFile(?string $path, string $role): void
    {
        $this->files()->wherePivot('role', $role)->detach();

        if (!$path) {
            return;
        }

        $file = File::createFromPath($path);

        $this->files()->attach($file->id, [
            'role' => $role,
        ]);
    }
}
