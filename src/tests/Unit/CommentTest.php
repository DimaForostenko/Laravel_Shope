<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_belongs_to_user()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_comment_belongs_to_product()
    {
        $product = Product::factory()->create();
        $comment = Comment::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $comment->product);
        $this->assertEquals($product->id, $comment->product->id);
    }

    public function test_comment_fillable_fields()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $comment = Comment::create([
            'content' => 'Great product!',
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
        ]);

        $this->assertEquals('Great product!', $comment->content);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($product->id, $comment->product_id);
        $this->assertEquals(4, $comment->rating);
    }
}