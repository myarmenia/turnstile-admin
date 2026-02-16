<?php

namespace App\Filament\Resources\OrderMessages\Pages;

use App\Filament\Resources\OrderMessages\OrderMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrderMessages extends ListRecords
{
    protected static string $resource = OrderMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
