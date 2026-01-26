<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected array $translations = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Отделяем translations из массива данных
        $this->translations = Arr::pull($data, 'translations');

        // Очищаем массив от полей файлов (Filament будет их обрабатывать через HasFiles)
        Arr::forget($data, ['main_image', 'slider', 'additional', 'videos', 'documents']);

        return $data; // массив, который реально пойдёт в products
    }

    protected function afterCreate(): void
    {
        $product = $this->record;

        // Сохраняем переводы
        foreach ($this->translations as $locale => $values) {
            $product->translations()->updateOrCreate(
                ['lang' => $locale],
                $values
            );
        }

        // Сохраняем файлы через HasFiles
        $data = $this->form->getState();

        if (!empty($data['main_image'])) {
            $product->syncSingleFile($data['main_image'], 'main');
        }

        if (!empty($data['slider'])) {
            $product->syncFiles($data['slider'], 'slider');
        }

        if (!empty($data['additional'])) {
            $product->syncFiles($data['additional'], 'additional');
        }

        if (!empty($data['videos'])) {
            $product->syncFiles($data['videos'], 'video');
        }

        if (!empty($data['documents'])) {
            $product->syncFiles($data['documents'], 'document');
        }
    }


}
