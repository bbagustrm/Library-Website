@extends('layouts.appAdmin')

@section('content')
    <div class="mx-auto w-full min-h-[100vh] flex">
        <!-- Sidebar -->
        <div class="w-1/4 min-h-full bg-white p-8 flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-lg py-2 px-6 hover:underline block">Dashboard</a>
            <a href="{{ route('admin.dataBooks') }}" class="text-lg font-semibold py-2 px-6 hover:bg-slate-200 block bg-slate-100 hover:underline rounded-full">Data Books</a>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 mx-12 my-8 px-8 py-4  bg-white rounded-lg">
            <h1 class="text-xl font-semibold">Update Books</h1>
            <form action="{{ route('admin.updateBook', $book->id) }}" method="POST" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="judul" class="block text-sm font-medium">Judul</label>
                    <input type="text" id="judul" name="judul" value="{{ $book->judul }}" class="w-full border p-2 rounded-md" required>
                </div>
                <div>
                    <label for="code" class="block text-sm font-medium">Code</label>
                    <input type="text" id="code" name="code" value="{{ $book->code }}" class="w-full border p-2 rounded-md" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium">Description</label>
                    <textarea id="description" name="description" class="w-full border p-2 rounded-md" rows="4">{{ $book->description }}</textarea>
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium">Image (URL)</label>
                    <input type="url" id="image" name="image" value="{{ $book->img }}" class="w-full border p-2 rounded-md" required>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium">Category</label>
                    <select id="category" name="category_id" class="w-full border p-2 rounded-md" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Update</button>
            </form>
        </div>
    </div>
@endsection
