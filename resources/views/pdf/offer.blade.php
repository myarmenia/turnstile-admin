<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">

<style>

*{
    font-family:"DejaVu Sans", sans-serif;
}

@page {
    margin: 0px
}

body{
    padding:120px 10px 210px 10px;
    font-size:14px;
    color:#333;
}


/* HEADER */

header{
    position:fixed;
    top:0;
    left:0;
    right:0;
    height:50px;
    border-bottom:1px solid #E5E7EB;
    padding: 20px 20px 0px;
    white-space:nowrap;
    background: #F9FAFB;

}

.header-left{
    float:left;
}

.header-right{
    float:right;
}

.logo{
    height:24px;
    vertical-align:middle;
    margin-right:8px;
}

.site-name{
    color:#155DFC;
    font-size:13px;
    margin-right:30px;
}

.header-right span{
    margin-left:10px;
    font-size:12px;
}

.icon-hd{
    height: 12px;
    vertical-align: middle;
    margin-left: 8px;
    margin-top: 10px
}



/* FOOTER */

footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px 20px;
    border-top: 1px solid #E5E7EB;
    font-size: 12px;
    background: #F9FAFB;

}

.footer-table {
    width: 100%;
    border-collapse: collapse;
}

.footer-table td {
    border: none;
    vertical-align: top;
    font-size: 12px;
}

.footer-left {
    width: 35%;
    text-align: left;
}

.footer-center {
    width: 32%;
    text-align: left;
}

.footer-right {
    width: 33%;
    text-align: left;
}

.footer-left .logo {
    height: 35px;
    vertical-align: middle;
    margin-right: 5px;
}

.footer-title {
    font-weight: bold;
    margin-bottom: 2px;
}

.footer-text {
    line-height: 1.4;
    font-size: 12px;
    margin-top: 8px
}

.footer-bottom {
    text-align: center;
    padding-top: 5px;
    font-size: 12px;
}

.icon {
    height: 12px;
    vertical-align: middle;
    margin-right: 4px;
    margin-top: 8px
}

.site-name-footer{
    color:#155DFC;
    font-size:13px;
    margin-right: 0px;
}



/* TITLE */

h1{
    text-align:center;
    margin-bottom:25px;
}


/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
    padding: 0px 20px;
}

th{
    border:1px solid #E5E7EB;
    background:#F9FAFB;
    padding:10px;
    text-align:left;
}

td{
    border:1px solid #E5E7EB;
    padding:10px;
}

.qty{
    text-align:center;
}

.price{
    text-align:right;
}

.total-row{
    font-weight:bold;
    background:#F9FAFB;
}

.product-image img{
    width:60px;
}

.body-top{
    margin: 20px 20px;
}

</style>

</head>


<body>
    <!-- HEADER -->
    <header>
        <div class="header-wrapper">

            <div class="header-left">
                <img src="{{ public_path('/images/logo.png') }}" class="logo">
                <span class="site-name">turniket.am</span>
            </div>
            <div class="header-right">
                <span><img src="{{ public_path('/images/icons/phone.png') }}" class="icon-hd"> +374 96 10 10 17</span>
                <span><img src="{{ public_path('/images/icons/phone.png') }}" class="icon-hd"> +374 96 40 00 73</span>
                <span><img src="{{ public_path('/images/icons/email.png') }}" class="icon-hd"> info@webex.am</span>
            </div>
        </div>
    </header>
    <!-- FOOTER -->

    <footer>
        <table class="footer-table">
            <tr>
                <!-- Левый блок: логотип + основной текст -->
                <td class="footer-left">
                    <img src="{{ public_path('/images/logo.png') }}" class="logo">
                    <p>Մասնագիտական լուծումներ մուտքի վերահսկման և անվտանգության համար</p>
                </td>
                <!-- Средний блок: контакты -->
                <td class="footer-center">
                    <div class="footer-title">Կոնտակտային տվյալներ</div>
                    <div class="footer-text">
                        <img src="{{ public_path('/images/icons/phone.png') }}" class="icon"> +374 96 10 10 17<br>
                        <img src="{{ public_path('/images/icons/phone.png') }}" class="icon"> +374 96 40 00 73<br>
                        <img src="{{ public_path('/images/icons/email.png') }}" class="icon"> info@webex.am
                    </div>
                </td>
                <!-- Правый блок: адрес -->
                <td class="footer-right">
                    <div class="footer-title">Հասցե</div>
                    <div class="footer-text">
                        <img src="{{ public_path('/images/icons/mappin.png') }}" class="icon"> Ք․ Երևան, Բաղրամյան 79 1/1
                    </div>
                </td>
            </tr>

            <!-- Вторая строка: копирайт -->
            <tr style="border-top: 1px solid #E5E7EB;  margin: 0px">
                <td colspan="3" style="padding:0;">
                    <table style="width:100%; border-collapse: collapse; margin: 0px; padding:0;">
                        <tr>
                            <td style="text-align:left; font-size:12px;">
                                © 2026 Turniket. Բոլոր իրավունքները պաշտպանված են։
                            </td>
                            <td style="text-align:right; font-size:12px" class="site-name-footer">
                                turniket.am
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </footer>

    <h1>Առաջարկ</h1>

    @php $total = 0 @endphp

    <table>

        <tr>
            <th width="10%">Կոդ</th>
            <th width="15%">Նկար</th>
            <th width="35%">Անվանում</th>
            <th width="10%">Քանակ</th>
            <th width="15%">Գին</th>
            <th width="15%">Գումար</th>
        </tr>


        @foreach ($items as $item)
            @php
                $product = $products->firstWhere('id',$item['product_id']);
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
                <td class="product-image">

                    @if ($product->mainImage())
                        <img src="{{ storage_path('app/public/'.$product->mainImage()->path) }}">
                    @endif
                </td>
                <td>
                    {{ $translation?->name }}
                </td>
                <td class="qty">
                    {{ $qty }}
                </td>
                <td class="price">
                    {{ number_format($price,0,'.',' ') }}
                </td>
                <td class="price">
                    {{ number_format($sum,0,'.',' ') }}
                </td>
            </tr>

        @endforeach


        <tr class="total-row">
            <td colspan="5" class="price">
                Ընդհանուր
            </td>
            <td class="price">
                {{ number_format($total,0,'.',' ') }}
            </td>
        </tr>

    </table>


    <div class="body-top" >
        <p>
            <b>Առաքման ժամկետ.</b>
            {{ $delivery_time }}
        </p>

        <p>
            <b>Առաջարկը վավեր է.</b>
            {{ $valid_until }}
        </p>
    </div>
</body>
</html>
