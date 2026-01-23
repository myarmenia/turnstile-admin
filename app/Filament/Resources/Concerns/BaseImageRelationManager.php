<?php

namespace App\Filament\Resources\Concerns;

use App\Models\File;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

abstract class BaseImageRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    /** role в pivot */
    protected static string $role = 'main';

    /** базовая папка */
    protected static string $directory = 'products';

    public function form(Schema  $form): Schema
    {
        return $form->schema([
            FileUpload::make('path')
                ->label('Изображение')
                ->image()
                ->disk('public')
                ->directory(
                    fn($livewire) =>
                    static::$directory . '/' . $livewire->ownerRecord->id
                )
                ->getUploadedFileNameForStorageUsing(
                    fn($file) => (string) str()->uuid() . '.' . $file->getClientOriginalExtension()
                )
                ->required()
                ->deletable(true),
        ]);
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('path')
                    ->disk('public')
                    ->label('Превью')
                    ->circular(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, $livewire) {
                        $path = $data['path'];

                        $file = \App\Models\File::createFromPath($path);

                        $livewire->ownerRecord->files()->attach($file->id, [
                            'role' => static::$role,
                            'sort_order' => 0,
                        ]);

                        return $file;
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->before(function ($record) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($record->path);
                        $record->delete();
                    }),
            ])
            ->filters([
                // Чтобы показывать только файлы с нужной ролью
                \Filament\Tables\Filters\Filter::make('role')
                    ->query(fn($query) => $query->where('fileables.role', static::$role)),
            ]);
    }
}
