@php

    $mime = $record->mime_type ?? '';
    $path = $record->path;
    $url = Storage::disk('public')->url($path);
    $style = 'padding: 5px; border: 1px solid #ddd; border-radius: 4px; margin-right: 5px;';
@endphp

@if(str_starts_with($mime, 'image/'))
    <img src="{{ $url }}" style="height: 80px; object-fit: cover; {{ $style }}">
@elseif(str_starts_with($mime, 'video/'))
    <video width="120" height="80" controls style="{{ $style }}">
        <source src="{{ $url }}" type="{{ $mime }}">
        Ваш браузер не поддерживает видео.
    </video>
@else
    <a href="{{ $url }}" target="_blank" style="{{ $style }}">
        📄 {{ basename($path) }}
    </a>
@endif


