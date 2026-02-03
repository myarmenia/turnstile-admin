<?php

namespace App\Filament\Resources\Concerns;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;

abstract class BaseProductFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';
    protected static string $role;

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('fileables.role', static::$role))
            ->reorderable('sort_order')
            ->defaultSort('fileables.sort_order')
            ->columns([
                ViewColumn::make('file')
                    ->label('Файл')
                    ->view('filament.columns.file-column'),

                TextColumn::make('title_ru')
                    ->label('Title (RU)')
                    ->getStateUsing(fn($record) => $this->getTranslation($record, 'ru', 'title')),

                TextColumn::make('alt_ru')
                    ->label('Alt (RU)')
                    ->getStateUsing(fn($record) => $this->getTranslation($record, 'ru', 'alt')),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn($record) => $this->buildTranslationForm($record))
                    ->mutateRecordDataUsing(function (array $data, $record): array {
                        // Заполняем форму данными из таблицы переводов
                        foreach (['hy', 'ru', 'en'] as $lang) {
                            $translation = $record->translations->firstWhere('lang', $lang);
                            $data["title_{$lang}"] = $translation?->title ?? '';
                            $data["alt_{$lang}"] = $translation?->alt ?? '';
                        }
                        return $data;
                    })
                    ->action(function ($record, array $data) {
                        // Вручную сохраняем переводы
                        $this->saveTranslations($record, $data);
                    }),

                DeleteAction::make()
                    ->before(fn($record) => $this->deleteFile($record)),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->before(fn($records) => collect($records)->each(fn($record) => $this->deleteFile($record))),
            ])
            ->paginated(false);
    }

    protected function getTranslation($record, string $lang, string $field): string
    {
        return $record->translations->firstWhere('lang', $lang)?->{$field} ?? '';
    }

    protected function buildTranslationForm($record): array
    {
        return collect(['hy', 'ru', 'en'])->flatMap(fn($lang) => [
            TextInput::make("title_{$lang}")
                ->label("Title ({$lang})")
                ->placeholder("Введите title на {$lang}")
                ->maxLength(255),

            TextInput::make("alt_{$lang}")
                ->label("Alt ({$lang})")
                ->placeholder("Введите alt на {$lang}")
                ->maxLength(255),
        ])->toArray();
    }

    protected function saveTranslations($record, array $data): void
    {
        foreach (['hy', 'ru', 'en'] as $lang) {
            $record->translations()->updateOrCreate(
                ['lang' => $lang],
                [
                    'title' => $data["title_{$lang}"] ?? null,
                    'alt' => $data["alt_{$lang}"] ?? null,
                ]
            );
        }
    }

    protected function deleteFile($record): void
    {
        Storage::disk('public')->delete($record->path);
    }
}
