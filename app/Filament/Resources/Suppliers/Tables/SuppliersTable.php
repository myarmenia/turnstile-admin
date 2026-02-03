<?php

namespace App\Filament\Resources\Suppliers\Tables;

use App\Filament\Traits\DynamicFilterTrait;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuppliersTable
{
    use DynamicFilterTrait;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('user_name')
                    ->label('Поставщики')
                    ->html(),

                TextColumn::make('wechat')
                    ->label('WeChat'),

                TextColumn::make('whatsapp')
                    ->label('WhatsApp'),

                TextColumn::make('price')
                    ->label('Цена'),

            ])
            ->filters(self::makeDynamicFilters([
                'user_name' => [
                    'label' => 'Поставщики',
                    'relation' => 'translations',
                    'column' => 'user_name',
                    'operator' => 'like',
                ],
                'wechat' => [
                        'label' => 'WeChat',
                        'column' => 'wechat',
                        'operator' => 'like',
                    ],
                'whatsapp' => [
                    'label' => 'WhatsApp',
                    'column' => 'whatsapp',
                    'operator' => 'like',
                ]
            ]))
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
