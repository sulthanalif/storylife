<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    /**
     * Menampilkan daftar galeri.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $galleries = Gallery::all();
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
        $gallery = Gallery::create([
            'tittle' => $request->tittle,
            'description' => $request->description,
            'file' => $request->file
        ]);

        return response()->json($gallery);
    }
    

    /**
     * Menampilkan detail galeri tertentu.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json('Galeri tidak ditemukan', 404);
        }
    
        return response()->json($gallery, 200);
    }

    /**
     * Menampilkan formulir untuk mengedit galeri tertentu.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json('Galeri tidak ditemukan', 404);
        }

    return response()->json($gallery, 200);
    }

    /**
     * Memperbarui galeri tertentu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tittle' => 'required|string', // It should be 'title' instead of 'tittle'
            'description' => 'required|string',
            'file' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $gallery = Gallery::find($id);
    
        if (!$gallery) {
            return response()->json('Galeri tidak ditemukan', 404);
        }
    
        // Update the gallery attributes
        $gallery->update([
            'tittle' => $request->input('tittle'), // It should be 'title'
            'description' => $request->input('description'),
            'file' => $request->input('file')
        ]);
    
        return response()->json('Berhasil Update galeri'.$gallery, 200);
    }

    /**
     * Menghapus galeri tertentu.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json('Galeri tidak ditemukan', 404);
        }
    
        $gallery->delete();
    
        return response()->json('Galeri berhasil dihapus', 200);
    }
}
