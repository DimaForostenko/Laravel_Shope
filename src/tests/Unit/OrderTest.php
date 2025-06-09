<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    public function test_order_has_many_order_items()
    {
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);

        $this->assertCount(1, $order->orderItems);
        $this->assertTrue($order->orderItems->first()->is($orderItem));
    }

    public function test_order_fillable_fields()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 200.00,
            'status' => 'pending',
        ]);

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(200.00, $order->total_amount);
        $this->assertEquals('pending', $order->status);
    }
}