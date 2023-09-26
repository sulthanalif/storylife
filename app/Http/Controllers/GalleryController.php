<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
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
            return ResponseFormatter::success($galleries, 'Berhasil Menampilkan Data Gallery');
        } else {
            return ResponseFormatter::error('', 'Gagal mengambil Data');
        }
        
    }

    /**
     * Menampilkan formulir untuk membuat galeri baru.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return ResponseFormatter::success('', 'Menampilkan Halaman Form Gallery');
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
            'file' => 'required',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return ResponseFormatter::error('', 'Validasi Gagal');
        }

        $tittle = $request->input('tittle');
        $description = $request->input('description');
        $file = $request->file('file')->getClientOriginalName();

        //memindahkan file ke repo
        $upload = $request->file('file')->move('upload', $file);

        //cek apakah bisa atau tidak
        if (!$upload) {
            return ResponseFormatter::error('', 'File Tidak Dapat Diupload!');
        }

        //simpan
        $galleries = Gallery::create([
            'tittle' => $tittle,
            'description' => $description,
            'file' => $file
        ]);

        if ($galleries) {
            return ResponseFormatter::success($galleries, 'Data Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'Data Gagal Disimpan');
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
            return ResponseFormatter::success($gallery, 'Data Ditemukan');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
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
            return ResponseFormatter::success($gallery, 'Data Ditemukan');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
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
            return ResponseFormatter::error('', 'Validasi Gagal');
        }

        //ambil data
        $tittle = $request->input('tittle');
        $description = $request->input('description');
        $file = $request->file('file')->getClientOriginalName();

        //memindahkan file ke repo
        $upload = $request->file('file')->move('upload', $file);

        //cek apakah bisa atau tidak
        if (!$upload) {
            return ResponseFormatter::error('', 'File Tidak Dapat Diupload!');
        }
        
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
                return ResponseFormatter::success($update, 'Data Berhasil Diubah');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Diubah');
            }
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
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
            // Hapus file terkait
            $fileToDelete = base_path('public/upload/' . $gallery->file);
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
            
            $gallery->delete();

            return ResponseFormatter::error('' , 'Data Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
    }
}
