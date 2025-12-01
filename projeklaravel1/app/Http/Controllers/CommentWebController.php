<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentWebController extends Controller
{
    public function store(Request $request, $slug)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk berkomentar.');
        }

        $article = Article::where('slug', $slug)->firstOrFail();

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'article_id' => $article->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}
