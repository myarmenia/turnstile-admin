<h1>Новая заявка на консультацию</h1>

<p><strong>Имя:</strong> {{ $data['full_name'] }}</p>
<p><strong>Телефон:</strong> {{ $data['phone_number'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Продукт:</strong> {{ $data['product_code'] }}</p>
<p><strong>Удобное время:</strong> {{ $data['preferred_time'] ?? '-' }}</p>
<p><strong>Комментарий:</strong> {{ $data['message'] ?? '-' }}</p>
