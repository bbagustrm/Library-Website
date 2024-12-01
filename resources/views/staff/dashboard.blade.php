@extends('layouts.appStaff')

@section('content')
    <div class="mx-auto w-full min-h-[100vh] flex">
        <!-- Sidebar -->
        <div class="w-1/4 min-h-full bg-white p-8 flex flex-col gap-2">
            <a href="{{ route('staff.dashboard') }}" id="dashboardLink"
                class="text-lg font-semibold py-2 px-6 hover:bg-slate-200 block bg-slate-100 hover:underline rounded-full">Dashboard</a>
            <a href="{{ route('staff.table') }}" id="tableLink" class="text-lg py-2 px-6 hover:underline block">Tabel Peminjaman</a>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 mx-12 my-8 px-8 py-2  bg-white rounded-lg">
            <!-- Tabs for Sections -->
            <div class="border-b border-gray-200 mb-6">
                <div class="flex space-x-8">
                    <a href="#" id="peminjamanTab"
                        class="text-lg font-medium text-blue-600 py-2 px-4 hover:text-blue-800">Peminjaman</a>
                    <a href="#" id="pengembalianTab"
                        class="text-lg font-medium text-blue-600 py-2 px-4 hover:text-blue-800">Pengembalian</a>
                </div>
            </div>

            <!-- Peminjaman Content -->
            <div id="peminjamanContent" class="">
                <h2 class="text-xl font-semibold mb-4">Peminjaman Buku</h2>
                <form action="{{ route('staff.peminjaman') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <input type="text" name="no_identitas" placeholder="no identitas"
                                class="w-full px-4 py-2 border border-gray-300 rounded" required>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="text" name="code_buku" id="code_buku" placeholder="code buku"
                                class="w-full px-4 py-2 border border-gray-300 rounded" required>
                            <button type="button" id="searchButton"
                                class="w-min-[500px] px-4 py-2 bg-blue-600 text-white rounded">Search</button>

                        </div>
                    </div>

                    <!-- Book Details Display -->
                    <div id="bookDetails" class="mt-4 hidden">
                        <h3 class="text-lg font-medium">Buku Ditemukan:</h3>
                        <div class="flex items-center space-x-8 mt-2 p-8 bg-white rounded-md">
                            <img id="bookImage" src="" alt="Book Image" class="w-[160px] rounded">
                            <div class="flex flex-col justify-between">
                                <p id="bookTitle" class="font-semibold text-lg"></p>
                                <p id="bookCode" class="text-gray-600"></p>
                                <p id="bookDescription" class="text-gray-600 text-sm"></p>
                                <p id="bookStatus" class="text-gray-600 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded">Tambah Peminjaman</button>
                </form>
            </div>

            <!-- Pengembalian Content -->
            <div id="pengembalianContent" class="hidden">
                <h2 class="text-xl font-semibold mb-4">Pengembalian Buku</h2>
                <form action="{{ route('staff.pengembalian') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <input type="text" name="no_identitas" placeholder="no identitas"
                                class="w-full px-4 py-2 border border-gray-300 rounded" required>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="text" name="code_buku" id="code_buku2" placeholder="code buku"
                                class="w-full px-4 py-2 border border-gray-300 rounded" required>
                            <button type="button" id="searchButton2"
                                class="w-min-[500px] px-4 py-2 bg-blue-600 text-white rounded">Search</button>

                        </div>
                    </div>

                    <!-- Book Details Display -->
                    <div id="bookDetails2" class="mt-4 hidden">
                        <h3 class="text-lg font-medium">Buku Ditemukan:</h3>
                        <div class="flex items-center space-x-8 mt-2 p-8 bg-white rounded-md">
                            <img id="bookImage2" src="" alt="Book Image" class="w-[160px] rounded">
                            <div class="flex flex-col justify-between">
                                <p id="bookTitle2" class="font-semibold text-lg"></p>
                                <p id="bookCode2" class="text-gray-600"></p>
                                <p id="bookDescription2" class="text-gray-600 text-sm"></p>
                                <p id="bookStatus2" class="text-gray-600 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 px-6 py-2 bg-green-600 text-white rounded">Proses
                        Pengembalian</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript untuk mengatur tampilan konten berdasarkan tab yang dipilih
        document.getElementById('peminjamanTab').addEventListener('click', function() {
            document.getElementById('peminjamanContent').classList.remove('hidden');
            document.getElementById('pengembalianContent').classList.add('hidden');
        });

        document.getElementById('pengembalianTab').addEventListener('click', function() {
            document.getElementById('pengembalianContent').classList.remove('hidden');
            document.getElementById('peminjamanContent').classList.add('hidden');
        });

        // JavaScript untuk pencarian buku berdasarkan code_buku
        document.getElementById('searchButton').addEventListener('click', function() {
            const codeBuku = document.getElementById('code_buku').value;

            if (codeBuku) {
                // Mengirimkan AJAX request ke server untuk mencari buku berdasarkan code_buku
                fetch(`/staff/search-buku/${codeBuku}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.book) {
                            document.getElementById('bookDetails').classList.remove('hidden');
                            document.getElementById('bookImage').src = data.book.image_url;
                            document.getElementById('bookTitle').textContent = data.book.title;
                            document.getElementById('bookCode').textContent = `Code: ${data.book.code}`;
                            document.getElementById('bookDescription').textContent = data.book.description;
                            let status = null;
                            if (data.book.status) {
                                document.getElementById('bookStatus').textContent = `Status: Tersedia`;
                            } else {
                                document.getElementById('bookStatus').textContent = `Status: Tidak Tersedia`;
                            }
                        } else {
                            alert('Buku tidak ditemukan!');
                        }
                    });
            }
        });
        document.getElementById('searchButton2').addEventListener('click', function() {
            const codeBuku = document.getElementById('code_buku2').value;

            if (codeBuku) {
                // Mengirimkan AJAX request ke server untuk mencari buku berdasarkan code_buku
                fetch(`/staff/search-buku/${codeBuku}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.book) {
                            document.getElementById('bookDetails2').classList.remove('hidden');
                            document.getElementById('bookImage2').src = data.book.image_url;
                            document.getElementById('bookTitle2').textContent = data.book.title;
                            document.getElementById('bookCode2').textContent = `Code: ${data.book.code}`;
                            document.getElementById('bookDescription2').textContent = data.book.description;
                            let status = null;
                            if (data.book.status) {
                                document.getElementById('bookStatus2').textContent = `Status: Tersedia`;
                            } else {
                                document.getElementById('bookStatus2').textContent = `Status: Tersedia`;
                            }
                        } else {
                            alert('Buku tidak ditemukan!');
                        }
                    });
            }
        });
    </script>
@endsection
