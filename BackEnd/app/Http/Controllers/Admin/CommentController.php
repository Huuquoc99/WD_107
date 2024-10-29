<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index()
    {
        $comment = Comment::all(); 
        return response()->json($comment, 200);
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->is_active == 0) {
            return response()->json(['message' => 'Comment is already hidden!'], 400);
        }

        $comment->is_active = 0; // Ẩn bình luận
        $comment->save();

        return response()->json(['message' => 'Comment has been hidden successfully.'], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }
}
