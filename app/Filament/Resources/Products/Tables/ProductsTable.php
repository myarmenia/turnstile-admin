<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Traits\DynamicFilterTrait;
use App\Models\Product;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class ProductsTable
{
    use DynamicFilterTrait;

    public static function configure(Table $table): Table
    {
        return $table
            ->query(Product::query()->with('translations'))
            ->columns([
                TextColumn::make('code')
                    ->label('Код товара')
                    ->sortable()
                    ->searchable(),

            TextColumn::make('name')
                ->label('Անվանում')
                ->getStateUsing(fn($record) => $record->translation('ru')?->name ?? '(нет названия)'),

                TextColumn::make('price')
                    ->label('Цена')
                    ->sortable(),

                ImageColumn::make('main_image')
                    ->label('Главная картинка')
                    ->getStateUsing(fn($record) => $record->mainImage()?->path),
            ])
            ->filters(self::makeDynamicFilters([
                'code' => [
                    'label' => 'Код',
                    'column' => 'code',
                    'operator' => 'like',
                ],
                'name' => [
                    'label' => 'Имя',
                    'relation' => 'translations',
                    'column' => 'name',
                    'operator' => 'like',
                ],
                'price' => [
                    'type' => 'range',
                    'label' => 'Цена ',
                    'column' => 'price'
                ]

            ]))
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('id', 'desc');

    }
}
