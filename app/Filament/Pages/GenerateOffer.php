<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class GenerateOffer extends Page
{
    // protected static ?string $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $navigationLabel = 'Коммерческое предложение';

    protected static ?string $title = 'Генерация коммерческого предложения';

    protected string $view = 'filament.pages.generate-offer';

    public ?array $items = [];

    public ?string $delivery_time = '70 օր';

    public ?string $valid_until = '';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Repeater::make('items')
                    ->label('Товары')
                    ->schema([

                        Select::make('product_id')
                            ->label('Товар')
                            ->options(Product::pluck('code', 'id'))
                            ->searchable()
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Количество')
                            ->numeric()
                            ->default(1)
                            ->required(),

                    ])
                    ->columns(2)
                    ->defaultItems(1),

                TextInput::make('delivery_time')
                    ->label('Срок доставки')
                    ->default('70 дней'),

                TextInput::make('valid_until')
                    ->label('Предложение действительно до'),

            ]);
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('generatePdf')
                ->label('Скачать PDF')
                ->action('generatePdf')

        ];
    }

    public function generatePdf()
    {
        $productIds = collect($this->items)->pluck('product_id');

        $products = Product::with(['translations'])->whereIn('id', $productIds)->get();

        $pdf = Pdf::loadView('pdf.offer', [

            'products' => $products,
            'items' => $this->items,
            'delivery_time' => $this->delivery_time,
            'valid_until' => $this->valid_until,

        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'offer.pdf'
        );
    }
}
