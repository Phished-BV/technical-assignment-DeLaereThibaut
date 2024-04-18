<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
<h1>Thank you for your order!</h1>
<p>Hello {{ $order->customer_name }},</p>
<p>We have received your order <strong>#{{ $order->id }}</strong> titled "{{ $order->order_title }}" and is currently {{$order->status}}</p>
<p>Order Details: {{ $order->order_details }}</p>
<p>We will notify you when your order status changes.</p>
</body>
</html>
