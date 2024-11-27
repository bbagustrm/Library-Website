<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Wishlist;

class ProfileController extends Controller
{
    public function index()
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
