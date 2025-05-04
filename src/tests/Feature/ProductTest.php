<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'category_id' => $category->id,
            'stock' => 10,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'name', 'price']);
    }
}