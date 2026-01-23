<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Concerns\BaseProductFilesRelationManager;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductSliderRelationManager extends BaseProductFilesRelationManager
{
    protected static string $role = 'slider';
    protected static ?string $title = 'Слайдер';
}

// class ProductSliderRelationManager extends BaseImageRelationManager
// {
//     protected static string $role = 'slider';
//     // protected static string $directory = 'products/slider';

//     // protected static ?string $title = 'Слайдер';
//     public function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 ImageColumn::make('path_url')
//                     ->label('Предпросмотр')
//                     ->height(100)
//                     ->width(100)
//                     ->square(),
//                 TextColumn::make('role')->label('Роль'),
//             ])
//             ->actions([
//                 DeleteAction::make(),
//             ])
//             ->headerActions([]);
//     }
// }
