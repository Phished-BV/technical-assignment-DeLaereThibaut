<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function receiveEmail(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'from' => 'required|string|max:255',
            'sender' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'body-plain' => 'required|string'
        ]);

        // Create a new order using the validated data
        $order = Order::create([
            'customer_name' => $validatedData['from'],
            'customer_email' => $validatedData['sender'],
            'order_title' => $validatedData['subject'],
            'order_details' => $validatedData['body-plain'],
            'status' => 'pending'
        ]);

        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            return response()->json(['success' => true, 'order_id' => $order->id], 201);  // 201 Created
        } catch (\Exception $e) {
            \Log::error('Mail send failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to send email.', 'details' => $e->getMessage()], 500); // 500 Internal Server Error
        }
    }
    public function editStatus(Order $order)
    {
        return view('orders.edit-status', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Accepted,Declined,In Progress,Shipped',
            'comment' => 'nullable|string'
        ]);

        $order->status = $request->status;
        $order->comment = $request->comment;
        $order->save();
        try {
            Mail::to($order->customer_email)->send(new OrderStatusUpdated($order));
            return redirect()->route('orders.index')->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Mail send failed: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('failed', 'Order status updating failed.');
        }

    }
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
}
