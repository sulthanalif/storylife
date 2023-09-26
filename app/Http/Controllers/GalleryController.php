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

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $galleries = Gallery::all();

        if ($galleries) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Memuat Data Gallery',
                'data' => $galleries
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Memuat Data',
                'data' => ''
            ], 400);
        }
        
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
        // Menentukan aturan validasi
        $rules = [
            'tittle' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|string',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 400);
        }

        $tittle = $request->input('tittle');
        $description = $request->input('description');
        $file = $request->input('file');

        $galleries = Gallery::create([
            'tittle' => $tittle,
            'description' => $description,
            'file' => $file
        ]);

        if ($galleries) {
            return response()->json([
                'success' => true,
                'message' => 'Data Gallery Berhasil Dibuat!',
                'data' => $galleries
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Membuat Data Gallery',
                'errors' => $galleries->errors()
            ], 400);
        }
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
        
        if ($gallery) {
            return response()->json([
                'success' => true,
                'message' => 'Data Ditemukan!',
                'data' => $gallery
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ada',
                'data' => ''
            ], 400);
        }
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
        
        if ($gallery) {
            return response()->json([
                'success' => true,
                'message' => 'Data Ditemukan!',
                'data' => $gallery
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ada',
                'data' => ''
            ], 400);
        }
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
        //rules
        $rules =[
            'tittle' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|string',
        ];

        //validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        //ambil data
        $tittle = $request->input('tittle');
        $description = $request->input('description');
        $file = $request->input('file');

        //cari data
        $gallery = Gallery::where('id', $id)->first();

        //validasi gallery
        if ($gallery) {
            $update = $gallery->update([
                'tittle' => $tittle,
                'description' =>$description,
                'file' => $file
            ]);

             //validasi update
            if ($update) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Diupdate!',
                    'data' => $gallery
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Gagal Diupdate',
                    'data' => ''
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan!',
                'data' => ''
            ], 400);
        }
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

        if ($gallery) {
            $gallery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Gallery Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 400);
        }
    }
}
