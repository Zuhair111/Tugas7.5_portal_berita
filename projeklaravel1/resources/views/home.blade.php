@extends('layouts.app')

@section('title', 'Portal Berita - Berita Terkini Indonesia')

@section('content')
<!-- Trending Topics -->
@if($featuredArticle || $trendingArticles->count() > 0)
<div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4 mb-6">
    <div class="flex items-center space-x-4 overflow-x-auto">
        <div class="flex items-center space-x-2 flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="font-semibold text-gray-800">Trending:</span>
        </div>
        @foreach($trendingArticles as $trending)
        <a href="/artikel/{{ $trending->slug }}" class="text-gray-700 hover:text-red-600 whitespace-nowrap">{{ $trending->title }}</a>
        @endforeach
    </div>
</div>
@endif

<!-- Featured Article -->
@if($featuredArticle)
<div class="mb-8">
    <a href="/artikel/{{ $featuredArticle->slug }}" class="block">
        <div class="relative rounded-xl overflow-hidden shadow-lg group">
            <img src="{{ $featuredArticle->image }}" alt="{{ $featuredArticle->title }}" class="w-full h-[400px] object-cover group-hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent">
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <span class="inline-block bg-red-600 px-3 py-1 rounded text-sm font-semibold mb-3">{{ $featuredArticle->category->name }}</span>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 leading-tight">{{ $featuredArticle->title }}</h1>
                    <p class="text-gray-200 text-sm">{{ $featuredArticle->time_ago }}</p>
                </div>
            </div>
        </div>
    </a>
</div>
@endif

<!-- Latest News Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @foreach($latestArticles as $article)
    <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
        <a href="/artikel/{{ $article->slug }}">
            <div class="relative h-48 overflow-hidden">
                <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                @if($article->is_trending)
                <span class="absolute top-3 left-3 bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">Trending</span>
                @endif
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-red-600">{{ $article->category->name }}</span>
                    <span class="text-xs text-gray-500">{{ $article->time_ago }}</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 hover:text-red-600">{{ $article->title }}</h3>
                <p class="text-gray-600 text-sm line-clamp-2">{{ $article->excerpt }}</p>
            </div>
        </a>
    </article>
    @endforeach
</div>

<!-- Category Sections -->
@foreach($categories->take(3) as $category)
@if($category->articles_count > 0)
<section class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
        <a href="/kategori/{{ $category->slug }}" class="text-red-600 hover:text-red-700 font-semibold text-sm">Lihat Semua →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($category->articles()->latest('published_at')->take(4)->get() as $article)
        <article class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow duration-300">
            <a href="/artikel/{{ $article->slug }}">
                <div class="h-32 overflow-hidden">
                    <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2 hover:text-red-600">{{ $article->title }}</h3>
                    <p class="text-xs text-gray-500">{{ $article->time_ago }}</p>
                </div>
            </a>
        </article>
        @endforeach
    </div>
</section>
@endif
@endforeach
@endsection
