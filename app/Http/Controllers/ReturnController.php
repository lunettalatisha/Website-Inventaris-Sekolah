<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReturnController extends Controller
{
    // Tampilkan daftar pengembalian
    public function index()
    {
        // buat view resources/views/returns/index.blade.php sesuai kebutuhan
        return view('returns.index');
    }

    // Simpan pengembalian baru
    public function store(Request $request)
    {
        // validasi & proses penyimpanan di sini
        // contoh sederhana:
        // $request->validate([...]);
        // ReturnModel::create([...]);

        return redirect()->back()->with('success', 'Pengembalian disimpan.');
    }
}
