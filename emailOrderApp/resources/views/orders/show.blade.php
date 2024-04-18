@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Order Details</h1>
        <div class="card">
            <div class="card-header">
                Order #{{ $order->id }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $order->order_title }}</h5>
                <p class="card-text"><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
                <p class="card-text"><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p class="card-text"><strong>Order Details:</strong> {{ $order->order_details }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $order->status }}</p>
                <p class="card-text"><strong>Order Created:</strong> {{ $order->created_at->toFormattedDateString() }}</p>
                <p class="card-text"><strong>Last Updated:</strong> {{ $order->updated_at->toFormattedDateString() }}</p>
                @if (!empty($order->comment))
                    <p  class="card-text"><strong>Comments:</strong> {{ $order->comment }}</p>
                @endif
                <a href="{{ route('orders.edit-status', $order->id) }}" class="btn btn-secondary">Edit Status</a>
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
