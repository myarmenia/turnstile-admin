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


