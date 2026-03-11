<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Supplier;
use App\Services\Categories\CategoryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
        $locales = ['hy' => 'Հայերեն', 'ru' => 'Русский', 'en' => 'English'];
        return $schema
            ->components([
                Group::make([
                    // Основные поля
                    TextInput::make('code')
                        ->label('Код товара')
                        ->required(),

                // Select::make('category_id')
                //     ->label('Категория')
                //     ->options(function (CategoryService $service) {
                //         $categories = $service->getActiveRows(['translations', 'children'])
                //             ->whereNull('parent_id');

                //         return self::buildCategoryOptions($categories);
                //     })
                //     ->searchable()
                //     ->required(),

                Select::make('category_id')
                    ->label('Категория')
                    ->options(self::getCategoryOptions())
                    ->searchable()
                    ->preload()
                    ->required(),

                    Select::make('supplier_id')
                        ->label('Поставщик')
                        ->options(Supplier::query()->pluck('user_name', 'id'))
                        ->searchable()
                        ->getOptionLabelFromRecordUsing(
                            fn(Supplier $supplier) =>
                            $supplier->user_name . ' - 111' . $supplier->company_name
                        ),

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
                                Repeater::make('main_image')
                                    ->label('Главная картинка')
                                    // ->relationship('files') // связь через fileables с role='main'
                                    ->defaultItems(1)
                                    ->schema(array_merge([
                                        FileUpload::make('path')
                                            ->image()
                                            ->directory(fn($record) => 'products/' . $record->id . '/main')
                                            ->disk('public')
                                            ->required(),
                                    ], array_reduce(array_keys($locales), function ($carry, $lang) use ($locales) {
                                        $carry[] = TextInput::make("translations.{$lang}.title")
                                            ->label("Title ({$locales[$lang]})")
                                            ->maxLength(255);
                                        $carry[] = TextInput::make("translations.{$lang}.alt")
                                            ->label("Alt ({$locales[$lang]})")
                                            ->maxLength(255);
                                        return $carry;
                                    }, [])))
                                    ->columns(1)
                                    ->createItemButtonLabel('Добавить картинку')
                            ]),

                            // ===== Слайдер =====
                            Tab::make('Слайдер')->schema([
                                Repeater::make('slider')
                                    ->label('Слайдер')
                                    ->schema(array_merge([
                                        FileUpload::make('path')
                                            ->image()
                                            ->directory(fn($record) => 'products/' . $record->id . '/slider')
                                            ->disk('public')
                                            ->required(),
                                    ], array_reduce(array_keys($locales), function ($carry, $lang) use ($locales) {
                                        $carry[] = TextInput::make("translations.{$lang}.title")
                                            ->label("Title ({$locales[$lang]})")
                                            ->maxLength(255)
                                            ->afterStateHydrated(function ($component, $state) use ($lang) {
                                                $product = $component->getLivewire()->getRecord();
                                                $mainImage = $product?->mainImage();

                                                if ($mainImage) {
                                                    $translation = $mainImage->translations()->where('lang', $lang)->first();
                                                    if ($translation) {
                                                        $component->state($translation->title); // для title
                                                    }
                                                }
                                            });

                                        $carry[] = TextInput::make("translations.{$lang}.alt")
                                            ->label("Alt ({$locales[$lang]})")
                                            ->maxLength(255)
                                            ->afterStateHydrated(function ($component, $state) use ($lang) {
                                                $product = $component->getLivewire()->getRecord();
                                                $mainImage = $product?->mainImage();

                                                if ($mainImage) {
                                                    $translation = $mainImage->translations()->where('lang', $lang)->first();
                                                    if ($translation) {
                                                        $component->state($translation->alt); // для alt
                                                    }
                                                }
                                            });

                                        return $carry;
                                    }, [])))
                                    ->columns(1)
                                    ->createItemButtonLabel('Добавить картинку'),
                            ]),

                            // Tab::make('Доп. файлы')->schema([
                            //     FileUpload::make('additional')
                            //         ->multiple()
                            //         ->directory(fn($record) => 'products/' . $record->id . '/additional')
                            //         ->disk('public'),
                            // ]),


                            // Tab::make('Видео')->schema([
                            //     Repeater::make('videos')
                            //         ->label('Видео')
                            //         ->schema(array_merge([
                            //             FileUpload::make('path')
                            //             ->acceptedFileTypes([
                            //                 'video/mp4',      // стандартный
                            //                 'video/x-m4v',    // иногда windows/mp4
                            //                 'video/mpeg',     // возможный fallback
                            //                 'video/webm',
                            //                 'video/quicktime'
                            //             ])
                            //                 ->directory(fn($record) => 'products/' . $record->id . '/videos')
                            //                 ->disk('public')
                            //                 ->maxSize(524288) // 512MB
                            //                 ->required(),
                            //         ], array_reduce(array_keys($locales), function ($carry, $lang) use ($locales) {
                            //             $carry[] = TextInput::make("translations.{$lang}.title")
                            //                 ->label("Title ({$locales[$lang]})")
                            //                 ->afterStateHydrated(function ($component, $state) use ($lang) {
                            //                     $product = $component->getLivewire()->getRecord();
                            //                     $mainImage = $product?->mainImage();

                            //                     if ($mainImage) {
                            //                         $translation = $mainImage->translations()->where('lang', $lang)->first();
                            //                         if ($translation) {
                            //                             $component->state($translation->title); // для title
                            //                         }
                            //                     }
                            //                 });

                            //             $carry[] = TextInput::make("translations.{$lang}.alt")
                            //                 ->label("Alt ({$locales[$lang]})")
                            //                 ->afterStateHydrated(function ($component, $state) use ($lang) {
                            //                     $product = $component->getLivewire()->getRecord();
                            //                     $mainImage = $product?->mainImage();

                            //                     if ($mainImage) {
                            //                         $translation = $mainImage->translations()->where('lang', $lang)->first();
                            //                         if ($translation) {
                            //                             $component->state($translation->alt); // для alt
                            //                         }
                            //                     }
                            //                 });

                            //             return $carry;
                            //         }, [])))
                            //         ->columns(1)
                            //         ->createItemButtonLabel('Добавить видео'),
                            // ]),

                            Tab::make('Видео')->schema([
                                Repeater::make('videos')
                                    ->label('Видео')
                                    ->schema([

                                        Select::make('type')
                                            ->label('Тип видео')
                                            ->options([
                                                'file' => 'Файл',
                                                'youtube' => 'YouTube ссылка',
                                            ])
                                            ->default('file')
                                            ->live()
                                            ->required(),

                                        // ===== Локальный файл =====
                                        FileUpload::make('path')
                                            ->label('Видео файл')
                                            ->acceptedFileTypes([
                                                'video/mp4',
                                                'video/x-m4v',
                                                'video/mpeg',
                                                'video/webm',
                                                'video/quicktime',
                                            ])
                                            ->directory(fn($record) => 'products/' . $record->id . '/videos')
                                            ->disk('public')
                                            ->maxSize(524288)
                                            ->visible(fn($get) => $get('type') === 'file')
                                            ->required(fn($get) => $get('type') === 'file'),

                                        // ===== YouTube URL =====
                                        TextInput::make('url')
                                            ->label('YouTube ссылка')
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->url()
                                            ->visible(fn($get) => $get('type') === 'youtube')
                                            ->required(fn($get) => $get('type') === 'youtube'),

                                    // ===== Переводы =====
                                    ...array_reduce(array_keys(self::SUPPORTED_LOCALES), function ($carry, $lang) {
                                        $label = self::SUPPORTED_LOCALES[$lang];

                                        $carry[] = TextInput::make("translations.{$lang}.title")
                                            ->label("Title ({$label})")
                                            ->afterStateHydrated(function ($component, $state) use ($lang) {

                                                // если уже есть значение — не перезаписываем
                                                if (!empty($state)) {
                                                    return;
                                                }

                                                $product = $component->getLivewire()->getRecord();
                                                $mainImage = $product?->mainImage();

                                                if ($mainImage) {
                                                    $translation = $mainImage->translations()
                                                        ->where('lang', $lang)
                                                        ->first();

                                                    if ($translation?->title) {
                                                        $component->state($translation->title);
                                                    }
                                                }
                                            });

                                        $carry[] = TextInput::make("translations.{$lang}.alt")
                                            ->label("Alt ({$label})")
                                            ->afterStateHydrated(function ($component, $state) use ($lang) {

                                                if (!empty($state)) {
                                                    return;
                                                }

                                                $product = $component->getLivewire()->getRecord();
                                                $mainImage = $product?->mainImage();

                                                if ($mainImage) {
                                                    $translation = $mainImage->translations()
                                                        ->where('lang', $lang)
                                                        ->first();

                                                    if ($translation?->alt) {
                                                        $component->state($translation->alt);
                                                    }
                                                }
                                            });

                                        return $carry;
                                    }, [])

                                    ])
                                    ->columns(1)
                                    ->createItemButtonLabel('Добавить видео'),
                            ]),

                            Tab::make('Документы')->schema([

                                    FileUpload::make('documents')
                                        ->label('Документы')
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


    private static function getCategoryOptions(): array
    {
        $service = app(CategoryService::class);

        $categories = $service->getActiveRows(['translations', 'children'])
            ->whereNull('parent_id');

        return self::buildCategoryOptions($categories);
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

            // Textarea::make("translations.{$locale}.description")
            //     ->label('Описание'),

            RichEditor::make("translations.{$locale}.short_description")
                ->label('Краткое описание'),

            RichEditor::make("translations.{$locale}.description")
                ->label('Описание'),

            RichEditor::make("translations.{$locale}.specifications")
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
