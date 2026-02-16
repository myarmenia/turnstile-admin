<?php

namespace App\Filament\Resources\OrderMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class OrderMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('full_name')
                    ->label('Имя клиента')
                    ->getStateUsing(fn($record) => $record->full_name ?: '-'),

                TextEntry::make('phone_number')
                    ->label('Телефон')
                    ->getStateUsing(fn($record) => $record->phone_number ?: '-'),

                TextEntry::make('email')
                    ->label('Email')
                    ->getStateUsing(fn($record) => $record->email ?: '-'),

                TextEntry::make('product_code')
                    ->label('Код товара')
                    ->getStateUsing(fn($record) => $record->product_code ?: '-'),

                TextEntry::make('message')
                    ->label('Сообщение')
                    ->wrap()
                    ->getStateUsing(fn($record) => $record->message ?: '-'),

                TextEntry::make('preferred_time')
                    ->label('Предпочтительное время')
                    ->getStateUsing(fn($record) => $record->preferred_time ?: '-'),

            ]);
    }
}
