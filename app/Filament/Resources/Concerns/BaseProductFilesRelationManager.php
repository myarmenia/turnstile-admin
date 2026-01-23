<?php

namespace App\Filament\Resources\Concerns;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Support\Facades\Storage;

// abstract class BaseProductFilesRelationManager extends RelationManager
// {
//     // Связь с таблицей файлов
//     protected static string $relationship = 'files';

//     // Каждая вкладка задаёт свою роль (main, slider, additional, video)
//     protected static string $role;

//     // Настройка таблицы
//     public function table(Table $table): Table
//     {

//         return $table
//             // Фильтруем файлы по роли
//             ->modifyQueryUsing(
//                 fn($query) =>
//                 $query->where('fileables.role', static::$role)
//             )
//             // Drag & drop сортировка
//             ->reorderable('sort_order')
//             ->defaultSort('fileables.sort_order')
//             // Колонки
//             ->columns([
//                 Tables\Columns\ImageColumn::make('path')
//                     ->disk('public')        // диск, где лежат картинки
//                     ->height(80)
//                     ->square()
//                     ->label('Файл'),
//             ])
//             // Действия на строку
//             ->actions([
//                 DeleteAction::make()
//                     ->before(fn($record) => Storage::disk('public')->delete($record->path)),
//             ])
//             // Массовые действия
//             ->bulkActions([
//                 DeleteBulkAction::make()
//                     ->before(
//                         fn($records) =>
//                         collect($records)->each(fn($record) => Storage::disk('public')->delete($record->path))
//                     ),
//             ])
//             ->paginated(false);
//     }
// }

abstract class BaseProductFilesRelationManager extends RelationManager
{
    // Связь с таблицей файлов
    protected static string $relationship = 'files';

    // Каждая вкладка задаёт свою роль (main, slider, additional, video, document)
    protected static string $role;

    public function table(Table $table): Table
    {
        return $table
            // Фильтруем файлы по роли
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('fileables.role', static::$role)
            )
            // Drag & drop сортировка
            ->reorderable('sort_order')
            ->defaultSort('fileables.sort_order')
            // Колонка с файлом (картинка, видео или документ)
            ->columns([
                ViewColumn::make('file')
                    ->label('Файл')
                    ->view('filament.columns.file-column')
                    ->sortable(false),
            ])
            // Действия на строку
            ->actions([
                DeleteAction::make()
                    ->before(fn($record) => Storage::disk('public')->delete($record->path)),
            ])
            // Массовые действия
            ->bulkActions([
                DeleteBulkAction::make()
                    ->before(
                        fn($records) =>
                        collect($records)->each(fn($record) => Storage::disk('public')->delete($record->path))
                    ),
            ])
            ->paginated(false);
    }
}
