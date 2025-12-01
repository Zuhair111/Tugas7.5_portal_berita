<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Get homepage data (featured, trending, latest articles)
     */
    public function index()
    {
        $featuredArticle = Article::with('category')
            ->where('is_featured', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->first();

        $trendingArticles = Article::with('category')
            ->where('is_trending', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestArticles = Article::with('category')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(10)
            ->get();

        $categories = Category::withCount('articles')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'featured' => $featuredArticle,
                'trending' => $trendingArticles,
                'latest' => $latestArticles,
                'categories' => $categories,
            ]
        ]);
    }

    /**
     * Get all categories
     */
    public function categories()
    {
        $categories = Category::withCount('articles')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get articles by category
     */
    public function categoryArticles($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $articles = Article::with('category')
            ->where('category_id', $category->id)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'articles' => $articles
            ]
        ]);
    }

    /**
     * Get article detail
     */
    public function show($slug)
    {
        $article = Article::with(['category', 'comments.user'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        // Increment views
        $article->incrementViews();

        // Get related articles
        $relatedArticles = Article::with('category')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'article' => $article,
                'related' => $relatedArticles
            ]
        ]);
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $articles = Article::with('category')
            ->where('published_at', '<=', now())
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Get trending articles
     */
    public function trending()
    {
        $articles = Article::with('category')
            ->where('is_trending', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Get popular articles (by views)
     */
    public function popular()
    {
        $articles = Article::with('category')
            ->where('published_at', '<=', now())
            ->orderBy('views', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }
}
