<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'buku_id' => 'required|exists:books,id',
        ]);

        // Periksa apakah sudah ada peminjaman dengan user_id dan buku_id yang sama
        $existingPeminjaman = Peminjaman::where('buku_id', $request->buku_id)
            ->where('user_id', Auth::user()->id)
            ->whereIn('status', ['belum'])
            ->first();

        if ($existingPeminjaman) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini.');
        }

        $existingPeminjaman2 = Peminjaman::where('buku_id', $request->buku_id)
            ->where('user_id', Auth::user()->id)
            ->whereIn('status', ['pengajuan'])
            ->first();

        if ($existingPeminjaman2) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan peminjaman untuk buku ini.');
        }
        // Buat peminjaman baru
        Peminjaman::create([
            'buku_id' => $request->buku_id,
            'user_id' => Auth::user()->id,
            'status' => 'pengajuan',
            'tenggat' => null,
            'denda' => 0,
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil dilakukan.');
    }
}
