<?php

namespace App\Filament\Widgets;

use App\Models\File;
use App\Models\Product;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;


class BaseProductFilesWidget extends Widget
{
    protected string $view = 'filament.widgets.product-files-widget';

    // Роль файлов, которую отображает виджет (main, slider, additional, video, document)
    public int $recordId;
    public string $role = 'main'; // текущая роль/вкладка

    public Product $product;
    public $files = [];

    protected array $roles = ['main', 'slider', 'additional', 'video', 'document'];

    public function mount(int $recordId, string $role = 'main'): void
    {
        $this->recordId = $recordId;
        $this->role = $role;
        $this->loadFiles();
    }

    // 🔹 Динамическая смена вкладки
    public function setRole(string $role): void
    {
        $this->role = $role;
        $this->loadFiles();
    }

    protected function loadFiles(): void
    {
        $this->product = Product::findOrFail($this->recordId);

        $this->files = $this->product
            ->files()
            ->wherePivot('role', $this->role)
            ->orderBy('pivot_sort_order')
            ->get();
    }

    public function deleteFile(int $fileId): void
    {
        $file = File::findOrFail($fileId);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        $this->loadFiles();
    }

    public function render(): View
    {
        return view($this->view, [
            'files' => $this->files,
            'roles' => $this->roles,
            'activeRole' => $this->role,
        ]);
    }
}



// abstract class BaseProductFilesWidget extends Widget
// {
//     protected string $view = 'filament.widgets.product-files-widget';

//     public int $recordId;
//     protected string $type;

//     public Product $product;
//     public $files = [];

//     public function mount(int $recordId): void
//     {
//         $this->recordId = $recordId;
//         $this->loadFiles();
//     }

//     protected function loadFiles(): void
//     {
//         $this->product = Product::findOrFail($this->recordId);

//         $this->files = $this->product
//             ->files()
//             ->where('role', $this->type)
//             ->get();
//     }

//     public function deleteFile(int $fileId): void
//     {
//         $file = File::findOrFail($fileId);

//         Storage::disk('public')->delete($file->path);
//         $file->delete();

//         $this->loadFiles();
//     }
// }
