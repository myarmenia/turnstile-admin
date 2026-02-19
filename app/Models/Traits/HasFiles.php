<?php
namespace App\Models\Traits;

use App\Models\File;

trait HasFiles
{

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable')
            ->withPivot(['role', 'sort_order'])
            ->orderBy('fileables.sort_order');
    }

    // ===== GETTERS =====

    public function mainImage()
    {
        return $this->files()->wherePivot('role', 'main')->with('translations')->first();
    }

    public function mainImagePath(): ?string
    {
        return $this->mainImage()?->path;
    }

    public function sliderImages()
    {
        return $this->files()
            ->wherePivot('role', 'slider')
            ->with('translations')
            ->orderBy('pivot_sort_order')
            ->get();
    }


    public function additionalFiles()
    {
        // здесь могут быть картинки, видео и документы
        return $this->files()
            ->wherePivot('role', 'additional')
            ->with('translations')
            ->orderBy('pivot_sort_order')
            ->get();
    }

    public function videos()
    {
        return $this->files()->wherePivot('role', 'video')->with('translations')->get();
    }

    public function documents()
    {
        return $this->files()->wherePivot('role', 'document')->with('translations')->get();
    }


    public function formatFileForApi(?File $file, string $lang = null): ?array
    {
        if (!$file) {
            return null;
        }

        $lang = $lang ?? request()->header('Accept-Language', 'ru');
        $translation = $file->translation($lang);

        return [
            // 'url' => asset('storage/' . $file->path),
            'url' => env('APP_FRONT_URL') . '/storage/' . $file->path,

            'title' => $translation?->title ?? '',
            'alt' => $translation?->alt ?? '',
            'type' => $file->type
        ];
    }

    // Получить основной файл с переводами для API
    public function getMainImageForApi(string $lang = null): ?array
    {
        return $this->formatFileForApi($this->mainImage(), $lang);
    }


    // Получить все файлы определенного типа для API
    public function getFilesByRoleForApi(string $role, string $lang = null): array
    {
        $method = match ($role) {
            'slider' => 'sliderImages',
            'additional' => 'additionalFiles',
            'video' => 'videos',
            'document' => 'documents',
            default => null
        };

        if (!$method) {
            return [];
        }

        return $this->$method()
            ->map(fn($file) => $this->formatFileForApi($file, $lang))
            ->filter()
            ->values()
            ->toArray();
    }




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



    public function addFile(string $path, string $role): ?File
    {

        if (!$path) {
            return null;
        }

        $file = File::createFromPath($path);

        $this->files()->attach($file->id, [
            'role' => $role,
        ]);

        return $file;
    }



    public function syncSingleFile(?string $path, string $role): ?File
    {
        $this->files()->wherePivot('role', $role)->detach();

        if (!$path) {
            return null;
        }

        $file = File::createFromPath($path);

        $this->files()->attach($file->id, [
            'role' => $role,
        ]);

        return $file;

    }


}
