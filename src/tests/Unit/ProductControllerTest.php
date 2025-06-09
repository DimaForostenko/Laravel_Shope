<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_store_validates_and_creates_product()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'Test Product',
            'description' => 'Test',
            'price' => 100.00,
            'category_id' => $category->id,
            'image' => 'test.jpg',
            'stock' => 10,
        ];

        $request = Request::create('/products', 'POST', $data);
        $controller = new ProductController();
        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }
}