@extends('layouts.app')

@section('title', $article->title . ' - Portal Berita')

@section('content')
<article class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="/" class="text-red-600 hover:text-red-700">Beranda</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="/kategori/{{ $article->category->slug }}" class="text-red-600 hover:text-red-700">{{ $article->category->name }}</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-600">{{ Str::limit($article->title, 50) }}</li>
        </ol>
    </nav>

    <!-- Article Header -->
    <header class="mb-6">
        <span class="inline-block bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold mb-3">{{ $article->category->name }}</span>
        <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $article->title }}</h1>
        <div class="flex items-center space-x-4 text-sm text-gray-600">
            <span>{{ $article->author }}</span>
            <span>•</span>
            <span>{{ $article->published_at->format('d M Y, H:i') }} WIB</span>
            <span>•</span>
            <span>{{ $article->views }} views</span>
        </div>
    </header>

    <!-- Featured Image -->
    <div class="mb-6 rounded-xl overflow-hidden">
        <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-auto">
    </div>

    <!-- Article Content -->
    <div class="prose max-w-none mb-8">
        <div class="text-xl text-gray-700 mb-4 font-semibold">{{ $article->excerpt }}</div>
        <div class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $article->content }}</div>
    </div>

    <!-- Tags & Share -->
    <div class="border-t border-b border-gray-200 py-4 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-gray-600 font-semibold">Bagikan:</span>
                <button class="p-2 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </button>
                <button class="p-2 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </button>
                <button class="p-2 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </button>
            </div>
        </div>
    </div>
</article>

<!-- Comments Section -->
<section class="max-w-4xl mx-auto mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Komentar ({{ $comments->total() }})</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Comment Form -->
    @auth
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('comment.store', $article->slug) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-semibold mb-2">Tulis Komentar</label>
                    <textarea name="content" id="content" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        placeholder="Bagikan pendapat Anda..."></textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                    Kirim Komentar
                </button>
            </form>
        </div>
    @else
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 mb-6 text-center">
            <p class="text-gray-700 mb-3">Silakan login untuk berkomentar</p>
            <a href="{{ route('login') }}" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                Login
            </a>
        </div>
    @endauth

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $comment->user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    @auth
                        @if($comment->user_id === auth()->id())
                            <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" 
                                onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
                <p class="text-gray-800">{{ $comment->content }}</p>
            </div>
        @empty
            <div class="bg-gray-100 rounded-lg p-6 text-center text-gray-600">
                Belum ada komentar. Jadilah yang pertama berkomentar!
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($comments->hasPages())
        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    @endif
</section>

<!-- Related Articles -->
@if($relatedArticles->count() > 0)
<section class="max-w-4xl mx-auto mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Berita Terkait</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($relatedArticles as $related)
        <article class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow duration-300">
            <a href="/artikel/{{ $related->slug }}" class="flex">
                <div class="w-32 h-32 flex-shrink-0">
                    <img src="{{ $related->image }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                </div>
                <div class="p-3 flex-1">
                    <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2 hover:text-red-600">{{ $related->title }}</h3>
                    <p class="text-xs text-gray-500">{{ $related->time_ago }}</p>
                </div>
            </a>
        </article>
        @endforeach
    </div>
</section>
@endif
@endsection
