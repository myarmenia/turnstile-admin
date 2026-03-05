<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Traits\DynamicFilterTrait;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class ProductsTable
{
    use DynamicFilterTrait;

    public static function configure(Table $table): Table
    {
        return $table
            ->query(Product::query()->with('translations'))
            ->columns([
                TextColumn::make('code')
                    ->label('Код товара')
                    ->sortable()
                    ->searchable(),

            TextColumn::make('name')
                ->label('Անվանում')
                ->getStateUsing(fn($record) => $record->translation('ru')?->name ?? '(нет названия)'),

                TextColumn::make('price')
                    ->label('Цена')
                    ->sortable(),

                ImageColumn::make('main_image')
                    ->label('Главная картинка')
                    ->getStateUsing(fn($record) => $record->mainImage()?->path),
            ])
            ->filters(self::makeDynamicFilters([
                'code' => [
                    'label' => 'Код',
                    'column' => 'code',
                    'operator' => 'like',
                ],
                'name' => [
                    'label' => 'Имя',
                    'relation' => 'translations',
                    'column' => 'name',
                    'operator' => 'like',
                ],
                'price' => [
                    'type' => 'range',
                    'label' => 'Цена ',
                    'column' => 'price'
                ]

            ]))
            ->actions([
                EditAction::make(),

                ActionGroup::make([
                    Action::make('pdf_ru')
                        ->label('Русский')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn(Product $record) => self::downloadPdf($record, 'ru')),

                    Action::make('pdf_hy')
                        ->label('Հայերեն')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn(Product $record) => self::downloadPdf($record, 'hy')),

                    Action::make('pdf_en')
                        ->label('English')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn(Product $record) => self::downloadPdf($record, 'en')),
                ])
                ->label('Скачать PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('danger'),
            ])
            ->defaultSort('id', 'desc');

    }



    protected static function downloadPdf(Product $record, string $locale)
    {
        $record->load(['translations', 'category']);

        $translation = $record->translation($locale);

        $pdf = Pdf::loadView('pdf.product', [
            'product' => $record,
            'translation' => $translation,
            'locale' => $locale,
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'product-' . $record->code . '-' . $locale . '.pdf'
        );
    }
}
