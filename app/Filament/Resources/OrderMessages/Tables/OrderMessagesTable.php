<?php

namespace App\Filament\Resources\OrderMessages\Tables;

use App\Filament\Traits\DynamicFilterTrait;
use App\Models\OrderMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderMessagesTable
{
    use DynamicFilterTrait;

    public static function configure(Table $table): Table
    {
        return $table
            ->query(OrderMessage::query()->with(['product']))
            ->columns([
                TextColumn::make('product_code')
                    ->label('Код товара')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('full_name')
                    ->label('Имя'),

                TextColumn::make('phone_number')
                    ->label('Телефон')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email'),

                TextColumn::make('preferred_time')
                    ->label('Предпочтительное'),

                TextColumn::make('message')
                    ->label('Сообщение')

            ])
            ->filters(self::makeDynamicFilters([
                'product_code' => [
                    'label' => 'Код',
                    'column' => 'product_code',
                    'operator' => 'like',
                ],
                'full_name' => [
                    'label' => 'Имя',
                    'column' => 'full_name',
                    'operator' => 'like',
                ],
                'phone_number' => [
                    'label' => 'Телефон',
                    'column' => 'phone_number',
                    'operator' => 'like',
                ],
            ]))
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
