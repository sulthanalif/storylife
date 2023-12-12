<?php

namespace App\Http\Controllers;

use App\Models\Status;
// use Faker\Provider\Image;
// use Illuminate\Auth\Events\Validated;
use App\Models\Service;
use App\Helpers\EncodeFile;

use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    // use EncodeFile;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::with('status')->get();

        $data = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'status' => $service->status->name,
                'name' => $service->name,
                'description' => $service->description,
                'image' => EncodeFile::encodeFile(base_path('public/upload/'. $service->image))
                // 'image' => $service->image
            ];
        });

        if ($data->isEmpty()) {
            return ResponseFormatter::error('', 'Belum Ada Data Pelayanan');
        }

        return ResponseFormatter::success($data, 'Data Berhasil Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Status::all('id', 'name');
        return ResponseFormatter::success($statuses, 'Halaman Create Service');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //rules
        $rules = [
            'name' => 'required|string',
            'description' => 'string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$services) {
                $name = $request->input('name');
                $description = $request->input('description');
                $status_id = $request->input('status_id');
                $file = $request->file('image');

                // Manipulasi gambar menggunakan Intervention Image
                $image = Image::make($file);
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $imageData = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $imageFileName = strtotime(date('Y-m-d H:i:s')) . '.' . $imageData . '.webp';

                // Simpan file dengan nama yang sudah dikodekan ke direktori yang sesuai
                $image->save(base_path() . '/' . env('UPLOADS_DIRECTORY') . '/' . $imageFileName, 90, 'webp');

                $services = Service::create([
                    'name' => $name,
                    'description' => $description,
                    'image' => $imageFileName,
                    'status_id' => $status_id
                ]);
            });

            if ($services) {
                return ResponseFormatter::success($services, 'Data Berhasil Disimpan');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Disimpan');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $service = Service::with('status')->where('id', $request->input('id'))->first();


        if ($service) {
            $data = [
                'id' => $service->id,
                'status' => $service->status->name,
                'name' => $service->name,
                'description' => $service->description,
                'image' => EncodeFile::encodeFile(base_path('public/upload/'. $service->image))
            ];

            return ResponseFormatter::success($data, 'Data Ditemukan');
        }
        return ResponseFormatter::error('', 'Data Tidak Ditemukan');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $service = Service::findOrFail($request->input('id'));

        if (!$service) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
        $data = [
            'id' => $service->id,
            'status' => $service->status->name,
            'name' => $service->name,
            'description' => $service->description,
            'image' => EncodeFile::encodeFile(base_path('public/upload/' . $service->image)),
        ];
        return ResponseFormatter::success($data, 'Data Ditemukan dan Masuk Halaman Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //rules
        $rules = [
            'name' => 'required|string',
            'description' => 'string',
            'image' => 'required',
            'status_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$services) {
                $service = Service::findOrFail($request->input('id'));
                if (!$service) {
                    return ResponseFormatter::error('', 'Data tidak ditemukan');
                }

                $delete = File::delete(base_path('public/upload/' . $service->image));

                if(!$delete) {
                    return ResponseFormatter::error('', 'Image Tidak Terhapus');
                }

                $name = $request->input('name');
                $description = $request->input('description');
                $status_id = $request->input('status_id');
                $file = $request->file('image');

                // Manipulasi gambar menggunakan Intervention Image
                $image = Image::make($file);
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Extract the filename without the extension
                $imageData = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $imageFileName = strtotime(date('Y-m-d H:i:s')) . '.' . $imageData . '.webp';
                //simpan
                $image->save(base_path() . '/' . env('UPLOADS_DIRECTORY') . '/' . $imageFileName, 90, 'webp');

                $services = $service->update([
                    'name' => $name,
                    'description' => $description,
                    'image' => $imageFileName,
                    'status_id' => $status_id
                ]);
            });
            if ($services) {
                return ResponseFormatter::success($services, 'Data Berhasil Diupdate');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Diupdate');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
        }
    }

    public function getList(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        if ($perPage === 'bypass' || $page === 'bypass') {
            // Jika per_page bernilai "bypass", gunakan metode bypass
            $services = Service::with( 'status')->get();
            $total = $services->count();
            $data = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'status' => $service->status->name,
                    'name' => $service->name,
                    'description' => $service->description,
                    'image' => EncodeFile::encodeFile(base_path('public/upload/'.$service->image)),
                ];
            });
        } else {
            // Jika per_page memiliki nilai selain "bypass", gunakan paginasi
            $paginator = Service::with( 'status')->paginate($perPage, ['*'], 'page', $page);
            $services = $paginator->items();
            $data = collect($services)->map(function ($service) {
                return [
                    'id' => $service->id,
                    'status' => $service->status->name,
                    'name' => $service->name,
                    'description' => $service->description,
                    'image' => EncodeFile::encodeFile(base_path('public/upload/'.$service->image)),
                ];
            });
            $total = $paginator->total();
        }

        $nextPageUrl = $perPage === 'bypass' || $page === 'bypass' ? null : PaginationHelper::getNextPageUrl($request, $page, $perPage, $total);
        $prevPageUrl = $perPage === 'bypass' || $page === 'bypass' ? null : PaginationHelper::getPrevPageUrl($request, $page, $perPage);

        return ResponseFormatter::success([
            'current_page' => (int)$page,
            'data' => $data,
            'next_page_url' => $nextPageUrl,
            'path' => $request->url(),
            'per_page' => (int)$perPage,
            'prev_page_url' => $prevPageUrl,
            'to' => (int)$page * (int)$perPage,
            'total' => (int)$total,
        ], 'Berhasil Menampilkan Data service');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $service = Service::findOrFail($request->input('id'));

        if ($service) {
            $service->delete();

            return ResponseFormatter::success('', 'Data Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $data = Service::withTrashed()->where('id', $request->input('id'))->first();
        $restore = $data->restore();

        if (!$restore) {
            return ResponseFormatter::error('', 'Gagal Restore Data');
        }

        return ResponseFormatter::success('', 'Berhasil Restore');
    }
}
