<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function test_index_returns_user_orders()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $request = Request::create('/orders', 'GET');
        $controller = new OrderController();
        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $response->getData(true));
    }

    public function test_store_creates_order_with_items()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100.00, 'stock' => 10]);
        $data = [
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ];

        $request = Request::create('/orders', 'POST', $data);
        $controller = new OrderController();
        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('total', $response->getData(true));
        $this->assertEquals(200.00, $response->getData(true)['total']);
        $this->assertCount(1, $response->getData(true)['order_items']);
    }

    public function test_show_returns_order_details()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $request = Request::create("/orders/{$order->id}", 'GET');
        $controller = new OrderController();
        $response = $controller->show($order,$request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('order_items', $response->getData(true));
    }

    public function test_update_changes_order_status()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $data = ['status' => 'completed'];

        $request = Request::create("/orders/{$order->id}", 'PUT', $data);
        $controller = new OrderController();
        $response = $controller->update($request, $order);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('completed', $response->getData(true)['status']);
    }

    public function test_destroy_deletes_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $request = Request::create("/orders/{$order->id}", 'DELETE');
        $controller = new OrderController();
        $response = $controller->destroy($order,$request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Order deleted', $response->getData(true)['message']);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}