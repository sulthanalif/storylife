<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Helpers\EncodeFile;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Menampilkan daftar galeri.
     *
     * @return \Illuminate\Http\JsonResponse
     */

//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Menentukan aturan validasi
        $rules = [
            'tittle' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required',
            'image' => 'required|image',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);   

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return ResponseFormatter::error('', 'Validasi Gagal');
        }

        $tittle = $request->input('tittle');
        $description = $request->input('description');
        $category_id = $request->input('category_id');
        $file = $request->file('image');
        $imageData = $file->getClientOriginalName();
        $image = EncodeFile::encodeFile($imageData);

        // Simpan file dengan nama yang sudah dikodekan ke direktori public/upload
        $file->move(base_path('public/upload'), $image);

        //simpan
        $galleries = Gallery::create([
            'tittle' => $tittle,
            'description' => $description,
            'category_id' => $category_id,
            'image' => $image
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
     * @param \App\Models\Gallery $gallery
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
     * @param \App\Models\Gallery $gallery
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //rules
        $rules = [
            'tittle' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required',
            'image' => 'required',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validasi Gagal');
        }

        //ambil data
        $tittle = $request->input('tittle');
        $description = $request->input('description');
        $category_id = $request->input('category_id');
        $file = $request->file('image');
        $imageData = EncodeFile::encodeFile($file);

        //cari data
        $gallery = Gallery::where('id', $id)->first();

    

        //validasi gallery
        if ($gallery) {
            $update = $gallery->update([
                'tittle' => $tittle,
                'description' => $description,
                'category_id' => $category_id,
                'image' => $imageData
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
     * @param \App\Models\Gallery $gallery
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

            return ResponseFormatter::success('', 'Data Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
    }

    public function getList()
    {
        $galleries = Gallery::all();

        if ($galleries) {
            return ResponseFormatter::success($galleries, 'Berhasil Menampilkan Data Gallery');
        } else {
            return ResponseFormatter::error('', 'Gagal mengambil Data');
        }
    }
}
