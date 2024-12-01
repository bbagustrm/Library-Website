@extends('layouts.appStaff')

@section('content')
    <div class="mx-auto w-full min-h-[100vh] flex">
        <!-- Sidebar -->
        <div class="w-1/4 min-h-full bg-white p-8 flex flex-col gap-2">
            <a href="{{ route('staff.dashboard') }}" id="dashboardLink"
                class="text-lg py-2 px-6 hover:underline block">Dashboard</a>
            <a href="{{ route('staff.table') }}" id="tableLink"
                class=" text-lg font-semibold py-2 px-6 hover:bg-slate-200 block bg-slate-100 hover:underline rounded-full">Tabel
                Peminjaman</a>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 mx-12 my-8 px-8 py-4  bg-white rounded-lg">
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
                            <th>Denda   </th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


        </div>
    </div>

    <script type="text/javascript">
        $(function () {
              
          var table = $('.data-table').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ route('staff.table') }}",
              columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'book.judul', name: 'book.judul'},
                {data: 'user.name', name: 'user.name'},
                {data: 'status', name: 'status'},
                {data: 'tenggat', name: 'tenggat'},
                {data: 'denda', name: 'denda'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
              ]
          });
              
        });
      </script>
@endsection
