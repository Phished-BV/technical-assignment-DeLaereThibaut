<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_receive_email_creates_order_and_sends_confirmation()
    {
        Mail::fake();
        $response = $this->postJson('/receive-email', [
            'from' => 'John Doe',
            'sender' => 'john@example.com',
            'subject' => 'New Order',
            'body-plain' => 'Details of the order here.'
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Order::all());

        Mail::assertSent(OrderConfirmation::class, 1);
    }

    public function test_index_displays_all_orders(): void
    {
        $order = Order::factory()->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($order->order_title);
    }

    public function test_show_displays_order_details()
    {
        $order = Order::factory()->create();

        $response = $this->get('/orders/' . $order->id);

        $response->assertStatus(200);
        $response->assertSee($order->order_title);
        $response->assertSee($order->customer_name);
    }

    public function user_can_update_order_status(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['status' => 'Pending']);

        $response = $this->actingAs($user)->post('/orders/' . $order->id . '/update-status', [
            'status' => 'Shipped'
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Shipped']);
    }

    public function test_update_status_updates_order_and_sends_email()
    {
        Mail::fake();
        $order = Order::factory()->create(['status' => 'Pending']);

        $response = $this->post('/orders/' . $order->id . '/update-status', [
            'status' => 'Shipped'
        ]);

        $response->assertRedirect('/');
        $this->assertEquals('Shipped', $order->fresh()->status);

        Mail::assertSent(OrderStatusUpdated::class, 1);
    }

    public function test_edit_status_displays_view_with_order_data()
    {
        $order = Order::factory()->create();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/orders/{$order->id}/edit-status");

        $response->assertStatus(200);
        $response->assertViewIs('orders.edit-status');
        $response->assertViewHas('order', $order);

        $response->assertSee('value="' . $order->status . '"', false);
    }

    public function test_update_status_handles_email_failure_gracefully()
    {
        Mail::fake();
        Log::shouldReceive('error')->once();


        Mail::shouldReceive('to')
            ->andThrow(new \Exception("Mail send failed: Simulated failure"));

        $user = User::factory()->create();
        $order = Order::factory()->create(['status' => 'Pending']);


        $response = $this->actingAs($user)->post('/orders/' . $order->id . '/update-status', [
            'status' => 'Shipped'
        ]);


        $response->assertRedirect('/');
        $response->assertSessionHas('failed', 'Order status updating failed.');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Shipped']);
    }

    public function test_receive_email_handles_email_sending_failure()
    {
        Mail::fake();

        Mail::shouldReceive('to')->once()->andThrow(new \Exception("Simulated email sending failure"));

        Log::shouldReceive('error')->once()->with('Mail send failed: Simulated email sending failure');

        $response = $this->json('POST', '/receive-email', [
            'from' => 'Jane Doe',
            'sender' => 'jane@example.com',
            'subject' => 'Another Order',
            'body-plain' => 'More order details.'
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'error' => 'Failed to send email.',
            'details' => 'Simulated email sending failure'
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'jane@example.com'
        ]);
    }

    public function test_order_mail_build()
    {
        $order = Order::factory()->make();
        $mailable = new OrderConfirmation($order);

        $mailable->build();

        $this->assertEquals('Order Confirmation', $mailable->subject);
        $this->assertEquals('email.confirmation', $mailable->view);
    }

    public function test_order_updated_build()
    {
        $order = Order::factory()->make();
        $mailable = new OrderStatusUpdated($order);

        $mailable->build();

        $this->assertEquals('Order Status Updated', $mailable->subject);
        $this->assertEquals('email.statusUpdated', $mailable->view);
        $this->assertArrayHasKey('order', $mailable->viewData);
        $this->assertSame($order, $mailable->viewData['order']);
    }
}
