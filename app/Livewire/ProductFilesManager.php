<?php

namespace App\Livewire;

use App\Models\File;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ProductFilesManager extends Component
{

    public Product $record;           // продукт
    public string $activeRole = 'main'; // текущая вкладка
    public array $roles = ['main', 'slider', 'additional', 'video', 'document']; // роли файлов
    public $files = [];               // файлы текущей роли

    public function mount(Product $record)
    {
        $this->record = $record;
        $this->loadFiles();
    }

    // Переключение роли (вкладки)
    public function setRole(string $role)
    {
        $this->activeRole = $role;
        $this->loadFiles();
    }

    // Загрузка файлов из pivot по роли
    protected function loadFiles()
    {
        $this->files = $this->record->files()
            ->wherePivot('role', $this->activeRole)
            ->orderBy('pivot_sort_order')
            ->get();
    }

    // Удаление файла
    public function deleteFile(int $fileId)
    {
        $file = File::findOrFail($fileId);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        $this->loadFiles();
    }

    public function render()
    {
        return view('livewire.product-files-manager', [
            'files' => $this->files,
            'activeRole' => $this->activeRole,
            'roles' => $this->roles,
        ]);
    }
}
