<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Concerns\BaseProductFilesRelationManager;

class ProductAdditionalFilesRelationManager extends BaseProductFilesRelationManager
{
    protected static string $role = 'additional';
    protected static ?string $title = 'Дополнительные файлы';
}
