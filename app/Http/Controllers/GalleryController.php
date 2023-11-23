<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Gallery;
use App\Models\Category;
use App\Helpers\EncodeFile;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PaginationHelper;

// use function Laravel\Prompts\select;

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
        $galleries = Gallery::with('category', 'status')->get();

        $data = $galleries->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'category' => $gallery->category->name,
                'status' => $gallery->status->name,
                'tittle' => $gallery->tittle,
                'description' => $gallery->description,
                'image' => EncodeFile::encodeFile(base_path('public/upload/'.$gallery->image)),
            ];
        });


        if ($galleries) {
            // $data = [];

            // foreach ($galleries as $gallery) {
            //     $data[] = [
            //         'id' => $gallery->id,
            //         'category' => $gallery->category->name,
            //         'title' => $gallery->title,
            //         'description' => $gallery->description,
            //         'image' => EncodeFile::encodeFile(base_path('public/upload/'. $gallery->image))
            //     ];
            // }

            return ResponseFormatter::success($data, 'Berhasil Menampilkan Data Gallery');
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
        $status = Status::select('id', 'name')->get();
        
        $category = Category::select('id', 'name')->get();

        $data = [
            'status' => $status,
            'category' => $category
        ];

        return ResponseFormatter::success($data, 'Menampilkan Halaman Form Gallery');
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
            'status_id' => 'required',
            'image' => 'required|image',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return ResponseFormatter::error('', 'Validasi Gagal');
        }

        try {
            DB::transaction(function () use ($request, &$galleries) {
                $tittle = $request->input('tittle');
                $description = $request->input('description');
                $category_id = $request->input('category_id');
                $status_id = $request->input('status_id');
                $file = $request->file('image');
                // mendapatkan original extensionnya
                $imageData = $file->getClientOriginalExtension();
                //membuat nama file dengan epochtime
                $image = strtotime(date('Y-m-d H:i:s')) . '.' . $imageData;
                $galleries = Gallery::create([
                    'tittle' => $tittle,
                    'description' => $description,
                    'category_id' => $category_id,
                    'status_id' => $status_id,
                    'image' => $image
                ]);
                // Simpan file dengan nama yang sudah dikodekan ke direktori public/upload
                $file->move(base_path('public/upload'), $image);
            });
            if ($galleries) {
                return ResponseFormatter::success($galleries, 'Data Berhasil Disimpan');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Disimpan');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
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
        $gallery = Gallery::with('category', 'status')->where('id', $id)->first();
        if ($gallery) {
            $data = [
                'id' => $gallery->id,
                'category' => $gallery->category->name,
                'status' => $gallery->status->name,
                'tittle' => $gallery->tittle,
                'description' => $gallery->description,
                //membuat image menjadi encode agar directory file tidak di temukan
                'image' => EncodeFile::encodeFile(base_path('public/upload/' . $gallery->image)),
            ];
            return ResponseFormatter::success($data, 'Data Ditemukan');
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
        $gallery = Gallery::with('category', 'status')->where('id', $id)->first();
        if ($gallery) {
            $data = [
                'id' => $gallery->id,
                'category' => [
                    'id' => $gallery->category->id,
                    'name' => $gallery->category->name
                ],
                'status' => [
                    'id' => $gallery->status->id,
                    'name' => $gallery->status->name
                ],
                'tittle' => $gallery->tittle,
                'description' => $gallery->description,
                'image' => EncodeFile::encodeFile(base_path('public/upload/'.$gallery->image)),
            ];
            return ResponseFormatter::success($data, 'Data Ditemukan');
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
            'status_id' => 'required',
            'image' => 'required',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validasi Gagal');
        }



        try {
            DB::transaction(function () use ($request, $id, &$galleries) {
                $gallery = Gallery::where('id', $id)->first();

                if (!$gallery) {
                    return ResponseFormatter::error('', 'Data tidak ditemukan');
                }

                $delete = File::delete(base_path('public/upload/' . $gallery->image));

                if(!$delete) {
                    return ResponseFormatter::error('', 'Image Tidak Terhapus');
                }

                $tittle = $request->input('tittle');
                $description = $request->input('description');
                $category_id = $request->input('category_id');
                $file = $request->file('image');
                // mendapatkan original extensionnya
                $imageData = $file->getClientOriginalExtension();
                //membuat nama file dengan epochtime
                $image = strtotime(date('Y-m-d H:i:s')) . '.' . $imageData;
                $galleries = $gallery->update([
                    'tittle' => $tittle,
                    'description' => $description,
                    'category_id' => $category_id,
                    'image' => $image
                ]);
                // Simpan file dengan nama yang sudah dikodekan ke direktori public/upload
                $file->move(base_path('public/upload'), $image);
            });
            if ($galleries) {
                return ResponseFormatter::success($galleries, 'Data Berhasil Disimpan');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Disimpan');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
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

    public function getList(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        if ($perPage === 'bypass') {
            // Jika per_page bernilai "bypass", gunakan metode bypass
            $galleries = Gallery::with('category', 'status')->get();
            $total = $galleries->count();
            $data = $galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'category' => $gallery->category->name,
                    'status' => $gallery->status->name,
                    'tittle' => $gallery->tittle,
                    'description' => $gallery->description,
                    'image' => EncodeFile::encodeFile(base_path('public/upload/'.$gallery->image)),
                ];
            });
        } else {
            // Jika per_page memiliki nilai selain "bypass", gunakan paginasi
            $paginator = Gallery::with('category', 'status')->paginate($perPage, ['*'], 'page', $page);
            $data = $paginator->items();
            $total = $paginator->total();
        }

        $nextPageUrl = $perPage === 'bypass' ? null : PaginationHelper::getNextPageUrl($request, $page, $perPage, $total);
        $prevPageUrl = $perPage === 'bypass' ? null : PaginationHelper::getPrevPageUrl($request, $page, $perPage);

        return ResponseFormatter::success([
            'current_page' => (int)$page,
            'data' => $data,
            'next_page_url' => $nextPageUrl,
            'path' => $request->url(),
            'per_page' => (int)$perPage,
            'prev_page_url' => $prevPageUrl,
            'to' => (int)$page * (int)$perPage,
            'total' => (int)$total,
        ], 'Berhasil Menampilkan Data Gallery');
    }

    // protected function getNextPageUrl(Request $request, $currentPage, $perPage, $total)
    // {
    //     if ($currentPage * $perPage < $total) {
    //         return $request->fullUrlWithQuery(['page' => $currentPage + 1]);
    //     }

    //     return null;
    // }

    // protected function getPrevPageUrl(Request $request, $currentPage, $perPage)
    // {
    //     if ($currentPage > 1) {
    //         return $request->fullUrlWithQuery(['page' => $currentPage - 1]);
    //     }

    //     return null;
    // }

    
}
