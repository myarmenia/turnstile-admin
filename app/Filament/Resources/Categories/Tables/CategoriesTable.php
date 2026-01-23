<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Filament\Traits\DynamicFilterTrait;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    use DynamicFilterTrait;

    public static function configure(Table $table): Table
    {
        return $table
            ->query(Category::query()->with(['translations', 'parent.translations']))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Название')
                    ->getStateUsing(fn($record) => self::renderIndentedName($record))
                    ->html(),

                TextColumn::make('parent_name')
                    ->label('Родительская категория')
                    ->getStateUsing(fn($record) => $record->parent?->translation('ru')?->name ?? '—'),

                // ToggleColumn::make('active')
                //     ->label('Ակտիվ'),

                ToggleColumn::make('active')
                    ->label('Активный')
                    ->afterStateUpdated(function ($record, $state) {
                        // при изменении статуса категории
                        // обновляем все подкатегории рекурсивно
                        self::updateChildrenState($record, $state);
                    }),
            ])
            ->filters(self::makeDynamicFilters([
                'name' => [
                    'label' => 'Название',
                    'relation' => 'translations',
                    'column' => 'name',
                    'operator' => 'like',
                ],
                'parent.name' => [
                    'label' => 'Родительская категория',
                    'relation' => 'parent.translations',
                    'column' => 'name',
                    'operator' => 'like',
                ],
                'active' => [
                    'type' => 'ternary',
                    'label' => 'Активный',
                    'trueLabel' => 'Да',
                    'falseLabel' => 'Нет',
                ],
            ]))
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    protected static function renderIndentedName($record, string $locale = 'ru'): string
    {
        $depth = $record->getDepth();
        $indent = str_repeat('➝ ', $depth);
        $icon = '📁 ';
        $name = e($record->translation($locale)?->name ?? '(անանուն)');
        return "<span>{$icon}{$indent}{$name}</span>";
    }

    protected static function updateChildrenState(Category $category, bool $state): void
    {
        foreach ($category->children as $child) {
            $child->update(['active' => $state]);
            self::updateChildrenState($child, $state); // рекурсия
        }
    }


}
