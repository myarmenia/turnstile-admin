<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;


class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;
    protected array $translations = [];

    // Перед заполнением формы подтягиваем переводы в нужном формате
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['translations'] = $this->record->translations->keyBy('locale')->map(function ($translation) {
            return [
                'name' => $translation->name,
                'slug' => $translation->slug,
            ];
        })->toArray();

        return $data;
    }

    // Перед сохранением отделяем переводы из данных
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->translations = $data['translations'] ?? [];
        unset($data['translations']);

        return $data;
    }

    // После сохранения обновляем или создаём переводы
    protected function afterSave(): void
    {
        foreach ($this->translations as $locale => $values) {
            $translation = $this->record->translations()->where('locale', $locale)->first();

            if ($translation) {
                $translation->update([
                    'name' => $values['name'] ?? '',
                    'slug' => $values['slug'] ?? '',
                ]);
            } else {
                $this->record->translations()->create([
                    'locale' => $locale,
                    'name' => $values['name'] ?? '',
                    'slug' => $values['slug'] ?? '',
                ]);
            }
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
