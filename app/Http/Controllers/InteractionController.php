<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    // Système de "Toggle" Like (Si j'aime, je like. Si je ré-appuie, j'enlève)
    public function toggleLike(Video $video)
    {
        $like = Like::where('user_id', Auth::id())
                    ->where('video_id', $video->id)
                    ->first();

        if ($like) {
            $like->delete();
            return response()->json(['status' => 'unliked', 'count' => $video->likes()->count()]);
        }

        Like::create([
            'user_id' => Auth::id(),
            'video_id' => $video->id
        ]);

        return response()->json(['status' => 'liked', 'count' => $video->likes()->count()]);
    }

    public function storeComment(Request $request, Video $video)
    {
        $request->validate(['content' => 'required|string|max:500']);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'video_id' => $video->id,
            'content' => $request->content
        ]);

        return response()->json([
            'status' => 'success',
            'comment' => $comment->content,
            'user_name' => Auth::user()->name
        ]);
    }
}