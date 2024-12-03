<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        $oneMonthAgo = Carbon::now()->subMonth(); // 1 bulan terakhir
        $today = Carbon::today(); // Hari ini

        // Data untuk chart peminjaman 1 bulan terakhir
        $peminjamanBulanan = Peminjaman::selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->where('created_at', '>=', $oneMonthAgo)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Data untuk peminjam hari ini
        $peminjamHariIni = Peminjaman::whereDate('created_at', $today)->count();

        return view('admin.dashboard', [
            'peminjamanBulanan' => $peminjamanBulanan,
            'peminjamHariIni' => $peminjamHariIni
        ]);
    }


    public function profile()
    {
        $userId = Auth::id();

        return view('admin.profile');
    }


    public function table(Request $request)
    {

        if ($request->ajax()) {
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

                    $btn = '<a href="' . route('admin.detail', $row->id) . '" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->rawColumns(['status', 'action']) // Tambahkan 'status' ke rawColumns
                ->make(true);
        }

        return view('admin.table');
    }

    public function detail($id)
    {
        $data = Peminjaman::where('id', $id)->with('book', 'user')->first();

        return view('admin.detailPeminjaman', compact('data'));
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

    public function dataBooks(Request $request)
    {
        // Statistik buku berdasarkan status
        $tersedia = Book::where('status', 1)->count(); // Buku tersedia
        $terpinjam = Book::where('status', 0)->count(); // Buku terpinjam
        
        if ($request->ajax()) {
            $data = Book::with('category')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = $row->status;

                    // Warna sesuai dengan status
                    $colorClass = match ($status) {
                        1 => 'bg-green-100',
                        0 => 'bg-red-100',
                        default => 'bg-gray-200',
                    };

                    return '<span class="px-2 py-1 text-sm rounded-full ' . $colorClass . '">' . ($status ? 'tersedia' : 'tidak tersedia') . '</span>';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="flex">' .
                        '<a href="' . route('admin.editBook', $row->id) . '" class="edit btn btn-warning btn-sm mr-2">Edit</a>' .
                        '<button class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Delete</button>' .
                        '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action']) // Tambahkan 'status' ke rawColumns
                ->make(true);
        }

        return view('admin.dataBooks', [
            'tersedia' => $tersedia,
            'terpinjam' => $terpinjam
        ]);
    }

    public function createBook(Request $request)
    {
        // Ambil semua kategori untuk dropdown pada form create
        $categories = Category::all();

        // Tampilkan view createBook dengan data kategori
        return view('admin.createBook', compact('categories'));
    }
    public function editBook($id)
    {
        // Ambil data buku berdasarkan ID
        $book = Book::findOrFail($id);

        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        // Tampilkan view updateBook dengan data buku dan kategori
        return view('admin.updateBook', compact('book', 'categories'));
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'image' => 'required|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        Book::create([
            'judul' => $request->judul,
            'code' => $request->code,
            'description' => $request->description,
            'img' => $request->image,
            'category_id' => $request->category_id,
            'status' => true,
        ]);

        return redirect()->route('admin.dataBooks')->with('success', 'Book created successfully!');
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'image' => 'required|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::findOrFail($id);
        $book->update([
            'judul' => $request->judul,
            'code' => $request->code,
            'description' => $request->description,
            'img' => $request->image,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.dataBooks')->with('success', 'Book updated successfully!');
    }

    public function deleteBook($id)
    {
        // Cari buku berdasarkan ID
        $book = Book::findOrFail($id);

        // Hapus buku
        $book->delete();

        // Kembalikan respon sukses (jika AJAX) atau redirect ke halaman tertentu
        return response()->json(['success' => 'Buku berhasil dihapus.']);
    }
}
