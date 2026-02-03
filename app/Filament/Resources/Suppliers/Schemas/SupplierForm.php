<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label('Название компании'),

                TextInput::make('user_name')
                    ->label('Контактное лицо')
                    ->required(),

                TextInput::make('wechat')
                    ->label('WeChat'),

                TextInput::make('whatsapp')
                    ->label('WhatsApp'),

                TextInput::make('price')
                    ->label('Цена')
                    ->numeric(),

                TextInput::make('email')
                    ->label('Email'),

                RichEditor::make('info')
                    ->label('Информация о поставщике')
            ]);
    }
}
