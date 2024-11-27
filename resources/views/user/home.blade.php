@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center my-24">
        <h1 class="text-2xl font-bold">Cari Buku Favoritmu</h1>
        <p class="text-gray-600">Lorem ipsum dolor sit amet, adipiscing elit.</p>
        <div class="mt-4">
            <form action="{{ route('user.home') }}" method="GET" class="flex justify-center gap-2">
                <input type="text" name="search" class="border border-gray-300 rounded px-4 py-2 w-full max-w-md focus:ring focus:ring-blue-300" placeholder="Masukkan judul buku..." value="{{ request('search') }}" />
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>

                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if (request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
            </form>
        </div>
    </div>

    <!-- Kategori dan Sort -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-2 ">
            <a href="{{ route('user.home') }}" class="text-sm px-4 py-2 border rounded-full {{ request('category') === null ? 'bg-blue-500 text-white' : 'border-gray-300 text-gray-700' }}">
                Semua
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('user.home', ['category' => $category->category_name, 'sort' => request('sort')]) }}" class="text-sm px-4 py-2 border rounded-full {{ request('category') === $category->category_name ? 'bg-blue-500 text-white' : 'border-gray-300 text-gray-700' }}">
                    {{ $category->category_name }}
                </a>
            @endforeach
        </div>
        <div>
            <select id="sortFilter" class="border border-gray-300 rounded px-3 py-2" onchange="applySort()">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </div>
    </div>

    <!-- Books -->
    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach ($books as $book)
            <a href="{{ route('books.show', $book->id) }}" class="bg-white shadow rounded overflow-hidden hover:shadow-md transition">
                <img src="{{ $book->img }}" alt="{{ $book->judul }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h5 class="text-lg font-semibold">{{ $book->judul }}</h5>
                    <p class="text-sm text-gray-600">Code: {{ $book->code }}</p>
                    <span class="inline-block mt-2 text-xs text-white bg-gray-600 px-2 py-1 rounded">{{ $book->category->category_name }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>

<script>
    function applySort() {
        const sort = document.getElementById('sortFilter').value;
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('category')) {
            window.location.href = `?category=${urlParams.get('category')}&sort=${sort}`;
        } else {
            window.location.href = `?sort=${sort}`;
        }
    }
</script>
@endsection
