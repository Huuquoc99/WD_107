<?php

namespace App\Http\Controllers\Client;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($product_id)
    {
        $comments = Comment::where('product_id', $product_id)->where('is_active', true)->get();
        return response()->json($comments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, $product_id)
    {
        $comment = Comment::create([
            'user_id' => $request->user_id,
            'product_id' => $product_id,
            'content' => $request->content,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Comment created successfully!', 'comment' => $comment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($product_id, $comment_id)
    {
        // http://localhost:8000/api/products/4/comments/8
        $comment = Comment::where('product_id', $product_id)->where('id', $comment_id)->firstOrFail();
        return response()->json($comment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Chưa commit
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, $product_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $comment->update($request->only('content'));
        return response()->json(['message' => 'Bình luận đã được cập nhật!', 'comment' => $comment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    
}
