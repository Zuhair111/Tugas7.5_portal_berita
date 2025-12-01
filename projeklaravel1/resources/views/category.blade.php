@extends('layouts.app')

@section('title', $category->name . ' - Portal Berita')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
    <p class="text-gray-600">{{ $articles->total() }} artikel ditemukan</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @foreach($articles as $article)
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

<!-- Pagination -->
<div class="flex justify-center">
    {{ $articles->links() }}
</div>
@endsection
