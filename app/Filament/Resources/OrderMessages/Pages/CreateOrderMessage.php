<?php

namespace App\Filament\Resources\OrderMessages\Pages;

use App\Filament\Resources\OrderMessages\OrderMessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderMessage extends CreateRecord
{
    protected static string $resource = OrderMessageResource::class;
}
