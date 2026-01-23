<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('price'),
                TextColumn::make('discount_price'),
                ImageColumn::make('main_image')
                    ->label('Главная картинка')
                    ->getStateUsing(fn($record) => $record->mainImage()?->path),
            ])->filters([])->actions([
                EditAction::make(),
            ]);
    }
}
