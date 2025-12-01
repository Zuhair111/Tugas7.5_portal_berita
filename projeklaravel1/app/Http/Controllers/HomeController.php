<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredArticle = Article::where('is_featured', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->first();

        $trendingArticles = Article::where('is_trending', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestArticles = Article::where('published_at', '<=', now())
            ->latest('published_at')
            ->take(6)
            ->get();

        $categories = Category::withCount('articles')->get();

        return view('home', compact('featuredArticle', 'trendingArticles', 'latestArticles', 'categories'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $articles = Article::where('category_id', $category->id)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::withCount('articles')->get();

        return view('category', compact('category', 'articles', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $article->incrementViews();

        $comments = $article->comments()->with('user')->paginate(10);

        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(4)
            ->get();

        $categories = Category::withCount('articles')->get();

        return view('article', compact('article', 'comments', 'relatedArticles', 'categories'));
    }
}
