<?php

namespace Tests\Unit;

use App\Http\Controllers\CommentController;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    public function test_index_returns_comments_for_product()
    {
        $product = Product::factory()->create();
        $comment = Comment::factory()->create(['product_id' => $product->id]);
        $request = Request::create('/comments', 'GET');
        $controller = new CommentController();

        $response = $controller->index($product); // Корекція: передаємо Product, а не ProductController

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $response->getData(true));
    }

    public function test_store_creates_comment()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $data = [
            'content' => 'Great product!',
            'rating' => 4,
        ];

        $request = Request::create('/comments', 'POST', $data);
        $controller = new CommentController();
        $response = $controller->store($request, $product); // Корекція

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Great product!', $response->getData(true)['content']);
        $this->assertEquals(4, $response->getData(true)['rating']);
        $this->assertEquals($user->id, $response->getData(true)['user_id']);
    }

    public function test_update_updates_comment()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);
        $data = ['content' => 'Updated comment', 'rating' => 5];

        $request = Request::create("/comments/{$comment->id}", 'PUT', $data);
        $controller = new CommentController();
        $response = $controller->update($request, $comment);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Updated comment', $response->getData(true)['content']);
        $this->assertEquals(5, $response->getData(true)['rating']);
    }

    public function test_destroy_deletes_comment()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);
        $request = Request::create("/comments/{$comment->id}", 'DELETE');
        $controller = new CommentController();
        $response = $controller->destroy($request, $comment);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Comment deleted', $response->getData(true)['message']);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}