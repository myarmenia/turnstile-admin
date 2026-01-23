<?php

namespace App\Filament\Widgets;

use App\Models\File;
use App\Models\Product;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class ProductFilesWidget extends Widget
{
    protected string $view = 'filament.widgets.product-files-widget';

    public int $recordId;        // ID продукта
    public ?string $type = null; // main, slider, additional
    public Product $product;
    public $files = [];

    // 🔹 Livewire слушатели — можно удалить, если не нужен emit
    // protected $listeners = ['filesUpdated' => 'loadFiles'];

    // 🔹 mount получает recordId и type
    public function mount(int $recordId, ?string $type = null)
    {
        $this->recordId = $recordId;
        $this->type = $type;

        $this->loadFiles();
    }

    // 🔹 Загружаем продукт и файлы
    public function loadFiles(): void
    {
        $this->product = Product::findOrFail($this->recordId);

        $query = $this->product->files();

        if ($this->type) {
            $query->where('role', $this->type);
        }

        $this->files = $query->get();
    }

    // 🔹 Удаляем файл
    public function deleteFile(int $fileId): void
    {
        $file = File::findOrFail($fileId);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        // Перезагружаем файлы после удаления
        $this->loadFiles();
    }

    // 🔹 Передаем данные в blade
    protected function getViewData(): array
    {
        return [
            'product' => $this->product,
            'files' => $this->files,
        ];
    }

    // 🔹 Добавляем метод render, чтобы файлы подтягивались на каждый рендер

    public function render(): View
    {
        $this->loadFiles();
        return view($this->view, $this->getViewData());
    }
}
