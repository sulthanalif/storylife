<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GaleryController extends Controller
{
    /**
     * Menampilkan daftar galeri.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $galleries = Galery::all();
        return response()->json($galleries, 200);
    }

    /**
     * Menampilkan formulir untuk membuat galeri baru.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json('Ini adalah halaman untuk membuat galeri', 200);
    }

    /**
     * Menyimpan galeri yang baru dibuat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi request
        $this->validate($request, [
            'tittle' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|string'
        ]);

        // Buat galeri baru
        $gallery = Galery::create([
            'tittle' => $request->tittle,
            'description' => $request->description,
            'file' => $request->file
        ]);

        return response()->json($gallery, 201);
    }
    

    /**
     * Menampilkan detail galeri tertentu.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Galery $galery)
    {
        return response()->json($galery, 200);
    }

    /**
     * Menampilkan formulir untuk mengedit galeri tertentu.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Galery $galery)
    {
        return response()->json('Ini adalah halaman untuk mengedit galeri', 200);
    }

    /**
     * Memperbarui galeri tertentu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Galery $galery)
    {
        $validatedData = $request->validate([
            'tittle' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|string'
        ]);

        $galery->update($validatedData);

        return response()->json($galery, 200);
    }

    /**
     * Menghapus galeri tertentu.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Galery $galery)
    {
        $galery->delete();

        return response()->json('Galeri berhasil dihapus', 200);
    }
}
