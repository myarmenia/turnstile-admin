<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Concerns\BaseProductFilesRelationManager;

class ProductMainImageRelationManager extends BaseProductFilesRelationManager
{
    protected static string $role = 'main';
    protected static ?string $title = 'Главная картинка';
}
