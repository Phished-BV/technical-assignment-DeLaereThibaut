@extends('layouts.app')  {{-- Extend your main layout --}}

@section('content')
    <div class="container">
        <h1>Order List</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Title</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('orders.show', $order->id) }}">{{ $order->id }}</a></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->order_title }}</td>
                    <td>{{ $order->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No orders found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
