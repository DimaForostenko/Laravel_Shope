<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_products()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertCount(1, $category->products);
        $this->assertTrue($category->products->first()->is($product));
    }

    public function test_category_fillable_fields()
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices',
        ]);

        $this->assertEquals('Electronics', $category->name);
        $this->assertEquals('electronics', $category->slug);
        $this->assertEquals('Electronic devices', $category->description);
    }
}