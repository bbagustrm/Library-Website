@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <a href="{{ url()->previous() }}" class="text-blue-500 mb-4 inline-block hover:underline">&larr; Kembali</a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Gambar Buku -->
            <div>
                <img src="{{ $book->img }}" alt="{{ $book->judul }}" class="rounded shadow w-full">
            </div>

            <!-- Detail Buku -->
            <div class="md:col-span-2">
                <h2 class="text-2xl font-bold">{{ $book->judul }}</h2>
                <p class="text-gray-700 mt-2"><strong>Code:</strong> {{ $book->code }}</p>
                <p class="text-gray-700">
                    <strong>Status:</strong>
                    @if ($book->status)
                        <span class="text-green-500">Tersedia</span>
                    @else
                        <span class="text-red-500">Tidak Tersedia</span>
                    @endif
                </p>
                <p class="text-gray-700"><strong>Genre:</strong> {{ $book->category->category_name }}</p>
                <p class="mt-4 text-gray-700"><strong>Description:</strong></p>
                <p class="text-gray-600">{{ $book->description ?? 'Tidak ada deskripsi.' }}</p>


                <div class="mt-6 flex gap-4">
                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $book->id }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajukan
                            Peminjaman</button>
                    </form>
                    <form action="{{ route('wishlist.store') }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <button type="submit" class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">
                            Add to Wishlist
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
