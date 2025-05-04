<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(ProductController $product)
    {
        $comments = $product->comments()->with('user')->get();
        return response()->json($comments);
    }

    public function store(Request $request, ProductController $product)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = $product->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'rating' => $validated['rating'],
        ]);

        return response()->json($comment->load('user'), 201);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment->update($validated);
        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
