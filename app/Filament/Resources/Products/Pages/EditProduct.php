<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected array $translations = [];

    /**
     * 1️⃣ Перед заполнением формы
     * Подтягиваем переводы в формат Filament
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['translations'] = $this->record
            ->translations
            ->keyBy('lang')
            ->map(fn($translation) => [
                'name'        => $translation->name ?? '',
                'slug'        => $translation->slug ?? '',
                'description' => $translation->description ?? '',
                'specifications' => $translation->specifications ?? '',
            ])
            ->toArray();

        return $data;
    }

    /**
     * 2️⃣ Перед сохранением
     * Отделяем translations, чтобы не писались в products
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translations = $data['translations'] ?? [];
        unset($data['translations']);

        // ❗️ файлы НЕ сохраняем напрямую в products
        unset(
            $data['main_image'],
            $data['slider'],
            $data['additional'],
            $data['videos'],
            $data['documents'],
        );

        return $data;
    }

    /**
     * 3️⃣ После сохранения
     * Обновляем переводы и файлы
     */
    protected function afterSave(): void
    {
        $product = $this->record;

        /**
         * 🔤 Переводы
         */
        foreach ($this->translations as $locale => $values) {
            $product->translations()->updateOrCreate(
                ['lang' => $locale],
                [
                    'name'        => $values['name'] ?? '',
                    'slug'        => $values['slug'] ?? '',
                    'description' => $values['description'] ?? '',
                    'specifications' => $values['specifications'] ?? '',

                ]
            );
        }

        /**
         * 📂 Файлы
         */
        $data = $this->form->getState();

        // if (!empty($data['main_image'])) {
        //     // $product->syncSingleFile($data['main_image'], 'main');
        //     $product->syncSingleFileWithSeo($this->filesData['main_image'][0], 'main');
        // }

        if (!empty($data['main_image'][0]['path'])) {

            $file = $product->syncSingleFile(
                $data['main_image'][0]['path'],
                'main'
            );

            // сохраняем SEO переводы
            if ($file && !empty($data['main_image'][0]['translations'])) {
                foreach ($data['main_image'][0]['translations'] as $lang => $values) {
                    $file->translations()->updateOrCreate(
                        ['lang' => $lang],
                        [
                            'title' => $values['title'] ?? null,
                            'alt'   => $values['alt'] ?? null,
                        ]
                    );
                }
            }
        }

        // if (!empty($data['slider'])) {
        //     $product->addFiles($data['slider'], 'slider');
        // }

        if (!empty($data['slider'])) {
            foreach ($data['slider'] as $item) {

                // Добавляем файл slider (если новый)
                $file = $this->record->addFile($item['path'], 'slider');

                // Берём SEO, если пользователь изменил, иначе оставляем прежние
                $translations = $item['translations'] ?? [];

                foreach ($translations as $lang => $values) {
                    $file->translations()->updateOrCreate(
                        ['lang' => $lang],
                        [
                            'title' => $values['title'] ?? null,
                            'alt'   => $values['alt']   ?? null,
                        ]
                    );
                }
            }
        }

        if (!empty($data['additional'])) {
            $product->addFiles($data['additional'], 'additional');
        }

        // if (!empty($data['videos'])) {
        //     $product->addFiles($data['videos'], 'video');
        // }

        if (!empty($data['videos'])) {
            foreach ($data['videos'] as $item) {

                // Добавляем файл videos (если новый)
                $file = $this->record->addFile($item['path'], 'video');

                // Берём SEO, если пользователь изменил, иначе оставляем прежние
                $translations = $item['translations'] ?? [];

                foreach ($translations as $lang => $values) {
                    $file->translations()->updateOrCreate(
                        ['lang' => $lang],
                        [
                            'title' => $values['title'] ?? null,
                            'alt'   => $values['alt']   ?? null,
                        ]
                    );
                }
            }
        }

        if (!empty($data['documents'])) {
            $product->addFiles($data['documents'], 'document');
        }
    }



    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }


}
