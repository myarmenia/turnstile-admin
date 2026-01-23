<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Concerns\BaseProductFilesRelationManager;

class ProductDocumentFilesRelationManager extends BaseProductFilesRelationManager
{
    protected static string $role = 'document';
    protected static ?string $title = 'Документ файлы';
}
