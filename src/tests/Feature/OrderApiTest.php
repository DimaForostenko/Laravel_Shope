<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_with_valid_data()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/orders', [
            'items' => [['product_id' => $product->id, 'quantity' => 2]]
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'user_id', 'total_amount', 'status', 'order_items']);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id]);
    }

    public function test_order_history_returns_user_orders()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 10]);
        $order = $user->orders()->create(['total_amount' => 200, 'status' => 'pending']);
        $order->orderItems()->create(['product_id' => $product->id, 'quantity' => 2, 'price' => 100]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/orders/history');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonStructure(['*' => ['id', 'user_id', 'total_amount', 'status', 'order_items' => ['*' => ['id', 'product_id', 'quantity', 'price']]]]);
    }

    public function test_authentication_required_for_orders()
    {
        $response = $this->postJson('/orders', ['items' => []]);
        $response->assertStatus(401);
    }
}