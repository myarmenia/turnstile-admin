<?php

namespace App\Filament\Resources\OrderMessages\Pages;

use App\Filament\Resources\OrderMessages\OrderMessageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderMessage extends ViewRecord
{
    protected static string $resource = OrderMessageResource::class;

    protected function getHeaderActions(): array
    {

        return [
            // EditAction::make(),
        ];
    }
}
