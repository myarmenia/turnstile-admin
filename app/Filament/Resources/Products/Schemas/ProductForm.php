<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Services\Categories\CategoryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductForm
{
    public const SUPPORTED_LOCALES = [
        'ru' => 'Русский',
        'hy' => 'Հայերեն',
        'en' => 'English',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    // Основные поля
                    TextInput::make('code')
                        ->label('Код товара')
                        ->required(),

                    Select::make('category_id')
                        ->label('Կատեգորիա')
                        ->options(function (CategoryService $service) {
                            $categories = $service->getActiveRows(['translations', 'children'])
                                ->whereNull('parent_id');

                            return self::buildCategoryOptions($categories);
                        })
                        ->searchable()
                        ->required(),

                    TextInput::make('price')
                        ->label('Цена')
                        ->numeric()
                        ->required(),

                    TextInput::make('discount_price')
                        ->label('Скидочная цена')
                        ->numeric(),

                    // ===== Переводы =====
                    Tabs::make('Translations')
                        ->tabs(
                            collect(self::SUPPORTED_LOCALES)
                                ->map(fn($label, $locale) => self::makeLangTab($locale, $label))
                                ->toArray()
                        ),

                    // ===== Файлы =====
                    Tabs::make('Files')
                        ->visible(fn($record) => $record)
                        ->tabs([
                        Tab::make('Главная картинка')->schema([
                            FileUpload::make('main_image')
                                ->image()
                                // ->directory('products/main')
                                ->directory(fn($record) => 'products/' . $record->id . '/main')
                                ->disk('public'),
                                // ->required(),
                        ]),
                        Tab::make('Слайдер')->schema([
                            FileUpload::make('slider')
                                ->multiple()
                                ->image()
                                ->directory(fn($record) => 'products/' . $record->id . '/slider')
                                ->disk('public'),
                        ]),
                        Tab::make('Доп. файлы')->schema([
                            FileUpload::make('additional')
                                ->multiple()
                                ->directory(fn($record) => 'products/' . $record->id . '/additional')
                                ->disk('public'),
                        ]),
                        Tab::make('Видео')->schema([
                            FileUpload::make('videos')
                                ->multiple()
                                ->acceptedFileTypes(['video/mp4', 'video/webm'])
                                ->directory(fn($record) => 'products/' . $record->id . '/videos')
                                ->disk('public')
                                ->maxSize(512_000) // 512MB
                                ->rules(['max:524288']) // 512MB в KB для Laravel
                                ->enableDownload(),
                        ]),
                        Tab::make('Документы')->schema([
                            FileUpload::make('documents')
                                ->multiple()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                ])
                                ->directory(fn($record) => 'products/' . $record->id . '/documents')
                                ->disk('public'),
                        ]),
                    ]),





            ])
            ]);
    }

    protected static function makeLangTab(string $locale, string $label): Tab
    {
        return Tab::make($label)->schema([
            TextInput::make("translations.{$locale}.name")
                ->label('Название')
                ->required(),

            TextInput::make("translations.{$locale}.slug")
                ->label('Slug')
                ->required(),

            TextInput::make("translations.{$locale}.description")
                ->label('Описание'),

            TextInput::make("translations.{$locale}.specifications")
                ->label('Характеристика'),
        ]);
    }


    private static function buildCategoryOptions($categories, $prefix = ''): array
    {
        $options = [];

        foreach ($categories as $category) {
            $options[$category->id] =
                $prefix . ($category->translation('ru')?->name ?? '(без названия)');

            if ($category->children->isNotEmpty()) {
                $options += self::buildCategoryOptions(
                    $category->children,
                    $prefix . '— '
                );
            }
        }

        return $options;
    }
}
