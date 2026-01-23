<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    // protected function getTableQuery(): Builder
    // {
    //     return parent::getTableQuery()->leftJoin('category_translations as ct', function ($join) {
    //         $join->on('categories.id', '=', 'ct.category_id')
    //             ->where('ct.locale', '=', 'hy'); // укажи нужную локаль
    //     })
    //         ->select('categories.*', 'ct.name as translated_name');
    // }

    

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
