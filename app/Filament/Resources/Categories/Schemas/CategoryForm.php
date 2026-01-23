<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Services\Categories\CategoryService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class CategoryForm
{
    public const SUPPORTED_LOCALES = [
        'ru' => 'Русский',
        'hy' => 'Հայերեն',
        'en' => 'English',
    ];

    protected static $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        self::$categoryService = $categoryService;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Select::make('parent_id')
                        ->label('Родительская категория')
                        ->options(fn($get) => self::getCategoryOptionsIndented(excludeId: $get('id')))
                        // ->getOptionLabelUsing(
                        //     fn($value): ?string =>
                        //     $value ? (Category::find($value)?->name . ' (#' . $value . ')') : null
                        // )
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Toggle::make('active')
                        ->label('Активный')
                        ->default(true),

                    Tabs::make('Translations')
                        ->tabs(
                            collect(self::SUPPORTED_LOCALES)->map(
                                fn($label, $locale) => self::makeLangTab($locale, $label)
                            )->toArray()
                        ),
                ])
            ]);
    }

    protected static function getCategoryOptionsIndented($categories = null, $prefix = '', $excludeId = null): array
    {
        $categoryService = app(CategoryService::class);

        $categories = $categories ?? $categoryService->getActiveRows(['translations'])->whereNull('parent_id');

        return $categories->mapWithKeys(function ($category) use ($prefix, $excludeId) {
            if ($excludeId && $category->id === $excludeId) {
                return [];
            }

            $name = $category->translation('hy')?->name ?? '(без названия)';

            // Только первый слой, без рекурсии
            return [(string) $category->id => $prefix . $name];
        })->all();
    }

    protected static function makeLangTab(string $locale, string $label): Tab
    {
        return Tab::make($label)->schema([
            TextInput::make("translations.{$locale}.name")
                ->label('Название')
                ->required(),

            TextInput::make("translations.{$locale}.slug")
                ->label('Slug')
                ->required()
                ->rule(function ($record) use ($locale) {
                    $translationId = $record?->translations
                        ?->firstWhere('locale', $locale)
                        ?->id;

                    $rule = Rule::unique('category_translations', 'slug')
                        ->where(fn($query) => $query->where('locale', $locale));

                    if ($translationId) {
                        $rule->ignore($translationId);
                    }

                    return $rule;
                })
        ]);
    }
}
