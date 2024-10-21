<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource (Read).
     */
    public function index()
    {
        // Mengambil semua data buku dari database
        $bukus = Buku::all();

        // Mengembalikan respons dengan data buku
        return response()->json($bukus);
    }

    /**
     * Store a newly created resource in storage (Create).
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255', // Nama buku tidak boleh kosong
            'penulis' => 'required|string|max:255',
            'harga' => 'required|numeric|min:1000', // Harga minimal Rp 1.000
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        // Menyimpan data ke dalam database
        $buku = Buku::create($validatedData);

        // Mengembalikan respons dengan data buku yang baru dibuat
        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }

    /**
     * Display the specified resource (Read single item).
     */
    public function show($id)
    {
        // Mencari buku berdasarkan ID
        $buku = Buku::find($id);

        // Jika buku tidak ditemukan
        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Mengembalikan respons dengan data buku
        return response()->json($buku);
    }

    /**
     * Update the specified resource in storage (Update).
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'judul' => 'sometimes|required|string|max:255', // Nama buku tetap wajib jika ada
            'penulis' => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|numeric|min:1000', // Harga minimal Rp 1.000 jika ada
            'stok' => 'sometimes|required|integer|min:0',
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
        ]);

        // Mencari buku berdasarkan ID
        $buku = Buku::find($id);

        // Jika buku tidak ditemukan
        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Memperbarui data buku
        $buku->update($validatedData);

        // Mengembalikan respons dengan data buku yang diperbarui
        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ]);
    }

    /**
     * Remove the specified resource from storage (Delete).
     */
    public function destroy($id)
    {
        // Mencari buku berdasarkan ID
        $buku = Buku::find($id);

        // Jika buku tidak ditemukan
        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Menghapus buku dari database
        $buku->delete();

        // Mengembalikan respons setelah buku dihapus
        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ]);
    }

    /**
     * Search books by category or title (Search).
     */
    public function search(Request $request)
    {
        // Validasi input 'judul' jika diperlukan
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        // Ambil query string dari request
        $judul = $request->input('judul');

        // Cari buku berdasarkan judul
        $buku = Buku::where('judul',  $judul )->get();

        // Jika buku tidak ditemukan
        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Jika buku ditemukan, kembalikan data buku
        return response()->json($buku);
    }
}