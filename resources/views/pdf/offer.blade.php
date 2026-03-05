<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        * {
            font-family: "DejaVu Sans", sans-serif;
        }

        body {
            padding: 40px;
            font-size: 14px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }

        table th {
            border: 1px solid #ccc;
            padding: 10px;
            background: #f3f3f3;
            text-align: left;
        }

        table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .price {
            text-align: right;
        }

        .qty {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background: #fafafa;
        }

        .product-block {
            margin-bottom: 35px;
        }

        .product-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-image {
            margin: 15px 0;
        }

        .product-image img {
            max-width: 260px;
        }

        .specifications {
            line-height: 1.6;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h1>Առաջարկ</h1>

    @php $total = 0 @endphp

    {{-- ===== ТАБЛИЦА ===== --}}

    <table>

        <tr>
            <th width="15%">Կոդ</th>
            <th width="35%">Անվանում</th>
            <th width="10%">Քանակ</th>
            <th width="20%">Միավոր Գին (AMD)</th>
            <th width="20%">Ընդհանուր (AMD)</th>
        </tr>

        @foreach ($items as $item)
            @php
                $product = $products->firstWhere('id', $item['product_id']);
                $translation = $product?->translation('hy');

                $price = $product->price ?? 0;
                $qty = $item['quantity'] ?? 0;
                $sum = $price * $qty;

                $total += $sum;
            @endphp

            <tr>

                <td>
                    {{ $product->code }}
                </td>

                <td>
                    {{ $translation?->name }}
                </td>

                <td class="qty">
                    {{ $qty }}
                </td>

                <td class="price">
                    {{ number_format($price, 0, '.', ' ') }}
                </td>

                <td class="price">
                    {{ number_format($sum, 0, '.', ' ') }}
                </td>

            </tr>
        @endforeach

        <tr class="total-row">

            <td colspan="4" class="price">
                Ընդհանուր
            </td>

            <td class="price">
                {{ number_format($total, 0, '.', ' ') }}
            </td>

        </tr>

    </table>


    {{-- ===== БЛОКИ ТОВАРОВ ===== --}}

    @foreach ($items as $item)
        @php
            $product = $products->firstWhere('id', $item['product_id']);
             $product->translations->firstWhere('locale', 'hy');
        @endphp

        <div class="product-block">

            <div class="product-title">
                {{ $product->code }} — {{ $translation?->name }}
            </div>

            @if ($product->mainImage())
                <div class="product-image">
                    {{-- env('APP_URL')-<img src="{{ env('APP_URL') . '/storage/' . $product->mainImage()->path }}"> --}}
                    <img src="{{ storage_path('app/public/'.$product->mainImage()->path)}}">
                    {{-- public_path-<img src="{{ public_path('storage/'.$product->mainImage()->path)}}">
                    file://-<img src="file://{{ public_path('storage/'.$product->mainImage()->path) }}"> --}}
                </div>
            @endif

            <div class="specifications">
                {!! $translation?->specifications !!}
            </div>
        </div>
    @endforeach


    <div class="footer">

        <p>
            <span class="label">Առաքման ժամկետ.</span>
            {{ $delivery_time }}
        </p>

        <p>
            <span class="label">Առաջարկը վավեր է.</span>
            {{ $valid_until }}
        </p>

    </div>

</body>

</html>
