@extends('layouts.app')  {{-- Extend your main layout --}}

@section('content')
    <div class="container">
        <h1>Update Order Status</h1>
        <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
            @csrf
            <div class="row">
                <label for="status">Choose a new status:</label>
                <select name="status" id="status">
                    <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Accepted" {{ $order->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="In Progress" {{ $order->status == 'In Progress' ? 'selected' : '' }}>In Progress
                    </option>
                    <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Declined" {{ $order->status == 'Declined' ? 'selected' : '' }}>Declined</option>
                </select>
            </div>
            <div class="row">
                <label for="comment">Add a comment:</label>
                <textarea name="comment" id="comment" rows="4" cols="50">{{ $order->comment }}</textarea>

            </div>
            <button type="submit">Update Status</button>
        </form>
    </div>
@endsection
