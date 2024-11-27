@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <!-- Profile Information -->
        <div class="bg-white shadow sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile Image" class="w-16 h-16 rounded-full">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->no_identitas }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500">Role: <span class="font-semibold">{{ Auth::user()->role }}</span></p>
                    </div>
                </div>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 mt-4 inline-block px-6 py-2 text-white rounded-md">
                        Logout
                    </button>
                </form>
            </div>

        </div>

        <!-- Tabs for Sections -->
        <div class="border-b border-gray-200 mb-6">
            <div class="flex space-x-8">
                <button onclick="showTab('pengajuan')" class="text-lg font-medium text-blue-600 py-2 px-4 hover:text-blue-800 focus:outline-none">Dalam Pengajuan</button>
                <button onclick="showTab('peminjaman')" class="text-lg font-medium text-blue-600 py-2 px-4 hover:text-blue-800 focus:outline-none">Dalam Peminjaman</button>
                <button onclick="showTab('histori')" class="text-lg font-medium text-blue-600 py-2 px-4 hover:text-blue-800 focus:outline-none">Histori Peminjaman</button>
                <button onclick="showTab('wishlist')" class="text-lg font-medium text-blue-600 py-2 px-4 hover:text-blue-800 focus:outline-none">Wishlist</button>
            </div>
        </div>

        <!-- Dalam Pengajuan Section -->
        <div id="pengajuan" class="tab-content hidden mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Dalam Pengajuan</h2>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($pengajuan as $item)
                    <a href="{{ route('books.show', $item->book->id) }}" class="bg-white shadow rounded-lg p-4">
                        <img src="{{ $item->book->img }}" alt="{{ $item->book->judul }}" class="w-full h-48 object-cover rounded mb-4">
                        <h4 class="font-bold">{{ $item->book->judul }}</h4>
                        <p class="text-sm text-gray-600">Code: {{ $item->book->code }}</p>
                        <span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                            {{ $item->book->category->category_name ?? 'Tidak Ada Kategori' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Dalam Peminjaman Section -->
        <div id="peminjaman" class="tab-content hidden mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Dalam Peminjaman</h2>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($peminjaman as $item)
                    <a href="{{ route('books.show', $item->book->id) }}" class="bg-white shadow rounded-lg p-4">
                        <img src="{{ $item->book->img }}" alt="{{ $item->book->judul }}" class="w-full h-48 object-cover rounded mb-4">
                        <h4 class="font-bold">{{ $item->book->judul }}</h4>
                        <p class="text-sm text-gray-600">Code: {{ $item->book->code }}</p>
                        <span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                            {{ $item->book->category->category_name ?? 'Tidak Ada Kategori' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Histori Peminjaman Section -->
        <div id="histori" class="tab-content hidden mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Histori Peminjaman</h2>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($histori as $item)
                    <a href="{{ route('books.show', $item->book->id) }}" class="bg-white shadow rounded-lg p-4">
                        <img src="{{ $item->book->img }}" alt="{{ $item->book->judul }}" class="w-full h-48 object-cover rounded mb-4">
                        <h4 class="font-bold">{{ $item->book->judul }}</h4>
                        <p class="text-sm text-gray-600">Code: {{ $item->book->code }}</p>
                        <span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                            {{ $item->book->category->category_name ?? 'Tidak Ada Kategori' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Wishlist Section -->
        <div id="wishlist" class="tab-content hidden mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Wishlist</h2>
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach ($wishlist as $item)
                    <a href="{{ route('books.show', $item->book->id) }}" class="bg-white shadow rounded-lg p-4">
                        <img src="{{ $item->book->img }}" alt="{{ $item->book->judul }}" class="w-full h-48 object-cover rounded mb-4">
                        <h4 class="font-bold">{{ $item->book->judul }}</h4>
                        <p class="text-sm text-gray-600">Code: {{ $item->book->code }}</p>
                        <span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                            {{ $item->book->category->category_name ?? 'Tidak Ada Kategori' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        // Function to show the clicked tab content and hide others
        function showTab(tabId) {
            // Hide all tab content
            let contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));

            // Show the selected tab content
            let selectedTabContent = document.getElementById(tabId);
            if (selectedTabContent) {
                selectedTabContent.classList.remove('hidden');
            }
        }

        // Optionally, you can activate the first tab by default
        document.addEventListener('DOMContentLoaded', function() {
            showTab('pengajuan'); // Default tab is "Dalam Pengajuan"
        });
    </script>
@endsection
