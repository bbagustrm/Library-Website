@extends('layouts.appAdmin')

@section('content')
    <div class="mx-auto w-full min-h-[100vh] flex">
        <!-- Sidebar -->
        <div class="w-1/4 min-h-full bg-white p-8 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" id="dashboardLink"
                class="text-lg font-semibold py-2 px-6 hover:bg-slate-200 block bg-slate-100 hover:underline rounded-full">Dashboard</a>
            <a href="{{ route('admin.dataBooks') }}" id="tableLink" class="text-lg py-2 px-6 hover:underline block">Data
                Books</a>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 mx-12 my-8 ">
            <div class="w-full mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Statistik Peminjaman</h1>
            
                <!-- Wrapper untuk dua kartu -->
                <div class="w-full flex gap-4">
                    <!-- Grafik Peminjaman -->
                    <div class="flex-1 bg-white rounded-lg p-4">
                        <div id="chart-peminjaman" class="h-64"></div>
                    </div>
            
                    <!-- Statistik Peminjam Hari Ini -->
                    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">👥 Peminjam Hari Ini</h2>
                        <div class="flex flex-col gap-2 items-center">
                            <p class="text-4xl font-bold text-blue-500">{{ $peminjamHariIni }}</p>
                            <span class="text-gray-500 mb-20">User</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-white rounded-lg">
                <h1 class="text-xl font-semibold">Tabel Peminjaman</h1>
                <div class="container mt-4">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Buku</th>
                                <th>Peminjam</th>
                                <th>Status</th>
                                <th>Tenggat</th>
                                <th>Denda </th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.table') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'book.judul',
                        name: 'book.judul'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'tenggat',
                        name: 'tenggat'
                    },
                    {
                        data: 'denda',
                        name: 'denda'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
    </script>
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Highcharts.chart('chart-peminjaman', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Statistik Peminjaman 1 Bulan Terakhir'
                },
                xAxis: {
                    categories: @json($peminjamanBulanan->pluck('tanggal')),
                    title: {
                        text: 'Tanggal'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Peminjaman'
                    }
                },
                series: [{
                    name: 'Peminjaman',
                    data: @json($peminjamanBulanan->pluck('jumlah'))
                }]
            });
        });
    </script>
@endsection
