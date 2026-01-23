<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Concerns\BaseProductFilesRelationManager;

class ProductVideoFilesRelationManager extends BaseProductFilesRelationManager
{
    protected static string $role = 'video';
    protected static ?string $title = 'Видео файлы';
}
