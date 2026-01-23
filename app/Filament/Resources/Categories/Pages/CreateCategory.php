<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;


class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    protected array $translations = [];
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Отделим translations перед сохранением
        $this->translations = Arr::pull($data, 'translations');
        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->translations as $locale => $values) {
            $this->record->translations()->create([
                'locale' => $locale,
                'name' => $values['name'] ?? '',
                'slug' => $values['slug'] ?? '',
            ]);
        }
    }
}
