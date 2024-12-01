<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Wishlist;


class UserController extends Controller
{
    public function index()
    {
        // Ambil semua kategori
        $categories = Category::all();

        // Ambil semua buku dengan status tersedia
        $query = Book::where('status', true)->with('category');

        // Filter berdasarkan kategori jika ada query string 'category'
        if (request()->has('category') && request('category') !== '') {
            $query->whereHas('category', function ($q) {
                $q->where('category_name', request('category'));
            });
        }

        // Filter berdasarkan urutan (terbaru/terlama)
        if (request()->has('sort') && in_array(request('sort'), ['latest', 'oldest'])) {
            $order = request('sort') === 'latest' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Filter berdasarkan pencarian judul
        if (request()->has('search') && request('search') !== '') {
            $query->where('judul', 'like', '%' . request('search') . '%');
        }

        $books = $query->get();

        // Kirim data ke view
        return view('user.home', compact('books', 'categories'));
    }

    public function show($id)
    {
        // Cari buku berdasarkan ID
        $book = Book::with('category')->findOrFail($id);

        return view('user.detail', compact('book'));
    }

    public function profile()
    {
        $userId = Auth::id();

        // Dalam Pengajuan
        $pengajuan = Peminjaman::where('user_id', $userId)
            ->where('status', 'pengajuan')
            ->with('book.category') // Eager load book dan category
            ->get();

        // Dalam Peminjaman
        $peminjaman = Peminjaman::where('user_id', $userId)
            ->where('status', 'belum')
            ->with('book.category') // Eager load book dan category
            ->get();

        // Histori Peminjaman
        $histori = Peminjaman::where('user_id', $userId)
            ->where('status', 'sudah')
            ->with('book.category') // Eager load book dan category
            ->get();

        // Wishlist
        $wishlist = Wishlist::where('user_id', $userId)
            ->with('book.category') // Eager load book dan category
            ->get();

        return view('user.profile', compact('pengajuan', 'peminjaman', 'histori', 'wishlist'));
    }
}
