<?php

namespace App\Filament\Resources\OrderMessages;

use App\Filament\Resources\OrderMessages\Pages\CreateOrderMessage;
use App\Filament\Resources\OrderMessages\Pages\EditOrderMessage;
use App\Filament\Resources\OrderMessages\Pages\ListOrderMessages;
use App\Filament\Resources\OrderMessages\Pages\ViewOrderMessage;
use App\Filament\Resources\OrderMessages\Schemas\OrderMessageForm;
use App\Filament\Resources\OrderMessages\Schemas\OrderMessageInfolist;
use App\Filament\Resources\OrderMessages\Tables\OrderMessagesTable;
use App\Models\OrderMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderMessageResource extends Resource
{
    protected static ?string $model = OrderMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'OrderMessage';

    public static function form(Schema $schema): Schema
    {
        return OrderMessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderMessagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrderMessages::route('/'),
            // 'create' => CreateOrderMessage::route('/create'),
            'view' => ViewOrderMessage::route('/{record}'),
            // 'edit' => EditOrderMessage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Сообщения заказов';
    }

    public static function getModelLabel(): string
    {
        return 'Сообщение заказа';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Сообщения заказов';
    }
}
