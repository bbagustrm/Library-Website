@extends('layouts.appAdmin')

@section('content')
    <div class="mx-auto w-full min-h-[100vh] flex">
        <!-- Sidebar -->
        <div class="w-1/4 min-h-full bg-white p-8 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" id="tableLink"
                class="text-lg py-2 px-6 hover:underline block">Dashboard</a>
            <a href="{{ route('admin.dataBooks') }}" id="tableLink"
                class="text-lg font-semibold py-2 px-6 hover:bg-slate-200 block bg-slate-100 hover:underline rounded-full">Data
                Books</a>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 mx-12 my-8 px-8 py-4  bg-white rounded-lg">
            <div class="flex justify-between">
                <h1 class="text-xl font-semibold">Data Books</h1>
                <a href="{{ route('admin.createBook') }}" class="edit btn btn-primary btn-sm mr-2">Create +</a>

            </div>
            <div class="container mt-4">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Category</th>
                            <th>Code</th>
                            <th>Status</th>
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
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.dataBooks') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'category.category_name',
                        name: 'category.category_name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            if (confirm("Apakah Anda yakin ingin menghapus buku ini?")) {
                $.ajax({
                    url: '/books/delete/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.success);
                        $('.data-table').DataTable().ajax.reload(); // Reload DataTable
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan. Gagal menghapus buku.');
                    }
                });
            }
        });
    </script>
@endsection
