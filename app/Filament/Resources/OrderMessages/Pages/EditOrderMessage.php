<?php

namespace App\Filament\Resources\OrderMessages\Pages;

use App\Filament\Resources\OrderMessages\OrderMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderMessage extends EditRecord
{
    protected static string $resource = OrderMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
