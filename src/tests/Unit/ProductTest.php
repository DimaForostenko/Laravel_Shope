<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_product_has_many_comments()
    {
        $product = Product::factory()->create();
        $comment = Comment::factory()->create(['product_id' => $product->id]);

        $this->assertCount(1, $product->comments);
        $this->assertTrue($product->comments->first()->is($comment));
    }

    public function test_product_has_many_order_items()
    {
        $product = Product::factory()->create();
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);

        $this->assertCount(1, $product->orderItems);
        $this->assertTrue($product->orderItems->first()->is($orderItem));
    }

    public function test_product_fillable_fields()
    {
        $category = Category::factory()->create();
        $product = Product::create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'description' => 'High-end laptop',
            'price' => 800.00,
            'image' => 'laptop.jpg',
            'category_id' => $category->id,
            'popularity' => 100,
            'stock' => 5,
        ]);

        $this->assertEquals('Laptop', $product->name);
        $this->assertEquals('laptop', $product->slug);
        $this->assertEquals('High-end laptop', $product->description);
        $this->assertEquals(800.00, $product->price);
        $this->assertEquals('laptop.jpg', $product->image);
        $this->assertEquals($category->id, $product->category_id);
        $this->assertEquals(100, $product->popularity);
        $this->assertEquals(5, $product->stock);
    }

    public function test_product_soft_deletes()
    {
        $product = Product::factory()->create();
        $product->delete();

        $this->assertSoftDeleted($product);
        $this->assertNull(Product::find($product->id));
        $this->assertNotNull(Product::withTrashed()->find($product->id));
    }
}