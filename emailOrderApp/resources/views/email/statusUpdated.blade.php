<!DOCTYPE html>
<html>
<head>
    <title>Order Status Updated</title>
</head>
<body>
<h1>Your Order Status Has Been Updated</h1>
<p>Hello {{ $order->customer_name }},</p>
<p>Your order with ID #{{ $order->id }} has been updated to the following status: <strong>{{ $order->status }}</strong>.</p>
@if (!empty($order->comment))
    <p>The following comments have been provided by the handler: {{ $order->comment }}</p>
@endif
<p>Thank you for doing business with us.</p>
</body>
</html>
