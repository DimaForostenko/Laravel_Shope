<?php

namespace Tests\Unit;

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Http\Request;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function test_index_returns_all_categories()
    {
        Category::factory()->count(3)->create();
        $request = Request::create('/categories', 'GET');
        $controller = new CategoryController();
        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, $response->getData(true));
    }

    public function test_store_creates_category()
    {
        $data = [
            'name' => 'Electronics',
            'description' => 'Electronic devices',
        ];

        $request = Request::create('/categories', 'POST', $data);
        $controller = new CategoryController();
        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('name', $response->getData(true));
        $this->assertEquals('Electronics', $response->getData(true)['name']);
    }

    public function test_update_updates_category()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        $request = Request::create("/categories/{$category->id}", 'PUT', $data);
        $controller = new CategoryController();
        $response = $controller->update($request, $category);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('New Name', $response->getData(true)['name']);
    }

    public function test_destroy_deletes_category()
    {
        $category = Category::factory()->create();
        $request = Request::create("/categories/{$category->id}", 'DELETE');
        $controller = new CategoryController();
        $response = $controller->destroy($category,$request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Category deleted', $response->getData(true)['message']);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}