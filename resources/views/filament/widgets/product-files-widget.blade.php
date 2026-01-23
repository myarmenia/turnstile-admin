<x-filament-widgets::widget>
    <div style="width: 100%; display: flex; gap: 16px; overflow-x: auto; padding: 0;">

        @foreach($files as $file)

            <div style="flex: 0 0 150px; background: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); position: relative; overflow: hidden;">

                {{-- Картинка --}}
                @if($file->type == 'image')
                    <img src="{{ asset('storage/' . $file->path) }}"
                         style="width: 100%; height: 100px; object-fit: cover;" alt="Image">

                {{-- Видео --}}
                @elseif($file->type === 'video')
                    <video controls style="width: 100%; height: 100px; object-fit: cover;">
                        <source src="{{ asset('storage/' . $file->path) }}" type="{{ $file->mime_type }}">
                    </video>

                {{-- Документы --}}
                @else
                    <div style="padding: 8px; font-size: 0.8rem;">
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank" style="color: #0d6efd; text-decoration: underline;">
                            {{ basename($file->path) }}
                        </a>
                    </div>
                @endif

                {{-- Кнопка удаления --}}
                <button wire:click="deleteFile({{ $file->id }})"
                        style="position: absolute; top: 4px; right: 4px; background: #dc3545; color: white; border: none; padding: 2px 6px; font-size: 0.7rem; border-radius: 4px; cursor: pointer;">
                    ×
                </button>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
