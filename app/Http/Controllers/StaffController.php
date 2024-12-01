<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('staff.dashboard', compact('books'));
    }

    public function storePeminjaman(Request $request)
    {
        // Validasi input user dan buku
        $request->validate([
            'no_identitas' => 'required|string',
            'code_buku' => 'required|string|exists:books,code',
        ]);

        // Mencari user berdasarkan no identitas
        $user = User::where('no_identitas', $request->no_identitas)->first();

        // Jika user tidak ditemukan, buat user baru
        if (!$user) {
            return redirect()->route('staff.dashboard')->with('error', 'no identitas tidak ditemukan');
        }

        // Mencari buku berdasarkan code
        $book = Book::where('code', $request->code_buku)->first();

        if (!$book->status) {
            return redirect()->route('staff.dashboard')->with('error', 'Buku tidak tersedia!');
        }
        // Jika buku tidak ditemukan, kembalikan pesan error (catatan: validasi sudah dilakukan pada `exists`)
        if (!$book) {
            return redirect()->route('staff.dashboard')->with('error', 'Buku tidak ditemukan!');
        }

        // Mengecek status peminjaman berdasarkan user_id dan buku_id
        $existingPeminjaman = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $book->id)
            ->orderByDesc('created_at')
            ->first();

        // Jika status terakhir adalah 'belum' atau 'pengajuan', kembalikan pesan error
        if ($existingPeminjaman && in_array($existingPeminjaman->status, ['belum', 'pengajuan'])) {
            return redirect()->route('staff.dashboard')->with('error', 'Peminjaman untuk buku ini sudah ada dan belum dikembalikan!');
        }

        // Jika tidak ada peminjaman aktif ('belum' atau 'pengajuan'), buat peminjaman baru
        Peminjaman::create([
            'user_id' => $user->id,
            'buku_id' => $book->id,
            'status' => 'belum', // Status baru untuk peminjaman
            'tenggat' => Carbon::now()->addDays(7), // Tambahkan tenggat waktu 7 hari (contoh)
            'denda' => 0, // Set denda awal ke 0
        ]);

        // Update status buku menjadi "tidak tersedia" (false)
        $book->update(['status' => false]);

        return redirect()->route('staff.dashboard')->with('success', 'Peminjaman berhasil dibuat!');
    }



    public function storePengembalian(Request $request)
    {
        // Validasi input user dan buku
        $request->validate([
            'no_identitas' => 'required|string',
            'code_buku' => 'required|string|exists:books,code',
        ]);

        // Mencari user berdasarkan no identitas
        $user = User::where('no_identitas', $request->no_identitas)->first();

        // Jika user tidak ditemukan, kembalikan pesan error
        if (!$user) {
            return redirect()->route('staff.dashboard')->with('error', 'User tidak ditemukan!');
        }

        // Mencari buku berdasarkan code
        $book = Book::where('code', $request->code_buku)->first();

        // Jika buku tidak ditemukan, kembalikan pesan error (catatan: validasi sudah dilakukan pada `exists`)
        if (!$book) {
            return redirect()->route('staff.dashboard')->with('error', 'Buku tidak ditemukan!');
        }

        // Mencari peminjaman berdasarkan user_id dan buku_id
        $existingPeminjaman = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $book->id)
            ->orderByDesc('created_at')
            ->first();

        // Logika untuk menangani status peminjaman
        if (!$existingPeminjaman || in_array($existingPeminjaman->status, ['sudah', 'pengajuan', null])) {
            return redirect()->route('staff.dashboard')->with('error', 'Peminjaman tidak ada atau sudah dikembalikan!');
        }

        if ($existingPeminjaman->status === 'belum') {
            // Hitung denda (contoh: Rp 1000 per hari keterlambatan)
            $today = Carbon::now();
            $tenggat = Carbon::parse($existingPeminjaman->tenggat);
            $diffInDays = $today->diffInDays($tenggat, false); // Hitung hari keterlambatan (false = negatif jika terlambat)
            $dendaPerHari = 1000;
            $denda = ceil(($diffInDays < 0 ? abs($diffInDays) * $dendaPerHari : 0) / 1000) * 1000;

            // Update status pengembalian
            $existingPeminjaman->update([
                'status' => 'sudah',
                'tenggat' => $today, // Simpan tanggal pengembalian
                'denda' => $denda,   // Simpan denda
            ]);

            // Update status buku menjadi tersedia
            $book->update(['status' => true]);

            // Berikan pesan sukses dengan denda
            $successMessage = $denda > 0
                ? "Buku berhasil dikembalikan! Denda keterlambatan: Rp " . number_format($denda, 0, ',', '.')
                : "Buku berhasil dikembalikan! Tidak ada denda.";

            return redirect()->route('staff.dashboard')->with('success', $successMessage);
        }

        return redirect()->route('staff.dashboard')->with('error', 'Terjadi kesalahan pada proses pengembalian!');
    }



    public function searchBuku($codeBuku)
    {
        // Mencari buku berdasarkan code_buku
        $book = Book::with('category')  // Pastikan relasi category dipanggil
            ->where('code', $codeBuku)
            ->first();

        if ($book) {
            return response()->json([
                'book' => [
                    'image_url' => $book->img,
                    'title' => $book->judul,
                    'code' => $book->code,
                    'description' => $book->description,
                    'status' => $book->status,
                ]
            ]);
        } else {
            return response()->json([
                'book' => null
            ]);
        }
    }

    public function profile()
    {
        $userId = Auth::id();

        return view('staff.profile');
    }


    public function table(Request $request)
    {

        if ($request->ajax()) {
            // $data = Peminjaman::with('book')->with('user')->get();

            $data = Peminjaman::with('book', 'user')
            ->orderByRaw("
                CASE 
                    WHEN status = 'pengajuan' THEN 1
                    WHEN status = 'belum' THEN 2
                    WHEN status = 'sudah' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu terbaru
            ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = $row->status;

                    // Warna sesuai dengan status
                    $colorClass = match ($status) {
                        'sudah' => 'bg-green-100',
                        'belum' => 'bg-red-100',
                        'pengajuan' => 'bg-yellow-100',
                        default => 'bg-gray-200',
                    };

                    return '<span class="px-2 py-1 text-sm rounded-full ' . $colorClass . '">' . $status . '</span>';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('staff.detail', $row->id) . '" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->rawColumns(['status', 'action']) // Tambahkan 'status' ke rawColumns
                ->make(true);
        }

        return view('staff.table');
    }

    public function detail($id)
    {
        $data = Peminjaman::where('id', $id)->with('book', 'user')->first();

        return view('staff.detailPeminjaman', compact('data'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pengajuan,sudah,belum',
        ]);

        // Cari data peminjaman berdasarkan ID
        $peminjaman = Peminjaman::findOrFail($id);

        // Update status berdasarkan permintaan
        $status = $request->input('status');
        $peminjaman->status = $status;

        // Ambil data buku terkait
        $book = $peminjaman->book;

        // Logika berdasarkan status
        switch ($status) {
            case 'sudah':
                // Set status buku menjadi tersedia (true)
                $book->status = true;

                // Hitung denda (Rp 1000 per hari keterlambatan)
                $today = Carbon::now();
                $tenggat = Carbon::parse($peminjaman->tenggat);
                $diffInDays = $today->diffInDays($tenggat, false); // Hitung hari keterlambatan
                $dendaPerHari = 1000;
                $peminjaman->denda = ceil(($diffInDays < 0 ? abs($diffInDays) * $dendaPerHari : 0) / 1000) * 1000;
                break;

            case 'belum':
                // Set status buku menjadi tidak tersedia (false)
                $book->status = false;

                // Set tenggat 1 minggu dari hari ini
                $peminjaman->tenggat = Carbon::now()->addDays(7);

                // Hitung denda (Rp 1000 per hari keterlambatan)
                $peminjaman->denda = 0; // Reset denda
                break;

            case 'pengajuan':
                // Set status buku menjadi tersedia (true)
                $book->status = true;

                // Set tenggat menjadi null
                $peminjaman->tenggat = null;

                // Set denda menjadi 0
                $peminjaman->denda = 0;
                break;
        }

        // Simpan perubahan data peminjaman dan buku
        $peminjaman->save();
        $book->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
