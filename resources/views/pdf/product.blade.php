<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 40px;
            color: #333;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .image {
            text-align: center;
            margin-bottom: 25px;
        }

        .info {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
        }

        .description, .specifications{
            margin-top: 6px;
            line-height: 1.5;
        }

        .box {
            border: 1px solid #ddd;
            padding: 20px;
        }
    </style>
</head>
<body>

@php
    $labels = [
        'ru' => [
            'code' => 'Код',
            'price' => 'Цена',
            'description' => 'Описание',
            'category' => 'Категория',
            'specifications' => 'Характеристика'
        ],
        'hy' => [
            'code' => 'Կոդ',
            'price' => 'Գին',
            'description' => 'Նկարագրություն',
            'category' => 'Կատեգորիա',
            'specifications' => 'Տեխնիկական բնութագիր'

        ],
        'en' => [
            'code' => 'Code',
            'price' => 'Price',
            'description' => 'Description',
            'category' => 'Category',
            'specifications' => 'Specifications'

        ],
    ];

    $t = $labels[$locale] ?? $labels['ru'];

@endphp

<div class="box">

    <h1>{{ $translation?->name ?? '—' }}</h1>

    @if($product->mainImage())
        <div class="image">
            {{-- <img src="{{ public_path('storage/'.$product->mainImage()->path) }}" width="250"> --}}
            <img src="{{ storage_path('app/public/'.$product->mainImage()->path) }}" width="250">
        </div>
    @endif

    <div class="specifications">
        <span class="label">{{ $t['specifications'] }}:</span>
        {!! $translation?->specifications ?? '—' !!}
    </div>

</div>

</body>
</html>
