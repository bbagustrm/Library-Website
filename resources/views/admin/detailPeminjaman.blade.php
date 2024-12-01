@extends('layouts.appAdmin')

@section('content')
    <div class="mx-auto w-full min-h-[100vh] flex">
        <!-- Sidebar -->
        <div class="w-1/4 min-h-full bg-white p-8 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" id="dashboardLink"
                class="text-lg py-2 px-6 hover:underline block">Dashboard</a>
            <a href="{{ route('admin.dashboard') }}" id="tableLink"
                class=" text-lg font-semibold py-2 px-6 hover:bg-slate-200 block bg-slate-100 hover:underline rounded-full">Tabel
                Peminjaman</a>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 mx-12 my-8 px-8 py-4 bg-white rounded-lg">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:underline block mb-4">
                &larr; Tabel Peminjaman
            </a>
            <h1 class="text-xl font-semibold">Detail Peminjaman</h1>
            <div class="container mt-8">
                <!-- Detail Data -->
                <div class="flex justify-between gap-2">
                    <!-- Left Section -->
                    <div class="flex-1 flex flex-col gap-3">
                        <div class="flex gap-12 items-center">
                            <p class="w-1/3">Nama Lengkap</p>
                            <p>{{ $data->user->name }}</p>
                        </div>
                        <div class="flex gap-12 items-center">
                            <p class="w-1/3">Nomor Identitas</p>
                            <p>{{ $data->user->no_identitas }}</p>
                        </div>
                        <div class="flex gap-12 items-center">
                            <p class="w-1/3">Email</p>
                            <p>{{ $data->user->email }}</p>
                        </div>
                        <div class="flex gap-12 items-center">
                            <p class="w-1/3">Tenggat</p>
                            <p>{{ $data->tenggat }}</p>
                        </div>
                        <div class="flex gap-12 items-center">
                            <p class="w-1/3">Status</p>
                            <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" 
                                    class="py-1 px-2 border rounded-md focus:outline-none focus:ring text-sm 
                                        {{ $data->status == 'pengajuan' ? 'bg-yellow-100' : ($data->status == 'belum' ? 'bg-red-100' : 'bg-green-100') }}">
                                    <option value="pengajuan" {{ $data->status == 'pengajuan' ? 'selected' : '' }}>pengajuan</option>
                                    <option value="sudah" {{ $data->status == 'sudah' ? 'selected' : '' }}>sudah</option>
                                    <option value="belum" {{ $data->status == 'belum' ? 'selected' : '' }}>belum</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex-1 flex flex-col gap-4 p-3 rounded-lg {{ $data->status == 'pengajuan' ? 'bg-yellow-100' : ($data->status == 'belum' ? 'bg-red-100' : 'bg-green-100') }}">
                        <div class="flex gap-4">
                            <img src="{{ $data->book->img }}" alt="Cover Buku" class="w-32 h-44 object-cover rounded-md">
                            <div>
                                <h2 class="font-bold text-xl">Judul Buku: {{ $data->book->judul }}</h2>
                                <p class="text-gray-600 text-sm">Code: {{ $data->book->kode }}</p>
                                <p class="mt-2 text-sm line-clamp-3">
                                    {{ $data->book->description ?? 'Deskripsi tidak tersedia.' }}
                                </p>
                                <div class="flex gap-2 mt-4">
                                    <span
                                        class="py-1 px-4 bg-white rounded-full text-sm">{{ $data->book->category->category_name ?? 'Kategori' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm">Tenggat: <span class="font-bold">{{ $data->tenggat ?? '-' }}</span></p>
                            <p class="text-sm">Denda: <span class="font-bold">{{ $data->denda ?? '-' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#status').on('change', function() {
                let status = $(this).val();
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.updateStatus', '') }}/" + id,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function(response) {
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('Gagal mengubah status. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endsection
