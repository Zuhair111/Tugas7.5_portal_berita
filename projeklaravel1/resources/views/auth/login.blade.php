@extends('layouts.auth')

@section('title', 'Login - Portal Berita')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Login</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded">
                    <span class="ml-2 text-gray-700">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200">
                Login
            </button>

            <p class="text-center text-gray-600 mt-4">
                Belum punya akun? <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-semibold">Daftar</a>
            </p>
        </form>
    </div>
</div>
@endsection
