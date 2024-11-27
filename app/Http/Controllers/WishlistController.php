<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Cek apakah kombinasi user_id dan book_id sudah ada di wishlist
        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->first();

        if ($existingWishlist) {
            return redirect()->back()->with('error', 'Buku sudah ada di wishlist Anda.');
        }

        // Tambahkan ke wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke wishlist.');
    }
}
