<?php

namespace App\Http\Controllers;

use App\Models\Service;
// use Faker\Provider\Image;
// use Illuminate\Auth\Events\Validated;
use App\Helpers\EncodeFile;
use Illuminate\Http\Request;

use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
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
        return ResponseFormatter::success('', 'Halaman Create Service');
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
            'image' => 'required',
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

                $imageData = $file->getClientOriginalExtension();

                $image = strtotime(date('Y-m-d H:i:s')) . '.' . $imageData;

                $services = Service::create([
                    'name' => $name,
                    'description' => $description,
                    'image' => $image,
                    'status_id' => $status_id
                ]);

                $file->move(base_path('public/upload'), $image);
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
    public function show($id)
    {
        $service = Service::with('status')->where('id', $id)->first();
        

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
    public function edit($id)
    {
        $data = Service::find($id);

        if (!$data) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        return ResponseFormatter::success($data, 'Data Ditemukan dan Masuk Halaman Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

        $name = $request->input('name');
        $description = $request->input('description');
        $status_id = $request->input('status_id');

        $file = $request->file('image');
        $imageData = EncodeFile::encodeFile($file);

        //cari data yg mau di edit
        $service = Service::find($id);

        if (!$service) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        $update = $service->update([
            'name' => $name,
            'description' => $description,
            'image' => $imageData,
            'status_id' => $status_id 
        ]);

        if (!$update) {
            return ResponseFormatter::error('', 'Data Gagal Diupdate');
        }

        return ResponseFormatter::success($update, 'Data Berhasil Terupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if ($service) {
            $service->delete();

            return ResponseFormatter::success('', 'Data Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Gagal Dihapus');
        }
    }

    public function restore($id) 
    {
        $data = Service::withTrashed()->where('id', $id)->first();
        $restore = $data->restore();

        if (!$restore) {
            return ResponseFormatter::error('', 'Gagal Restore Data');
        }

        return ResponseFormatter::success('', 'Berhasil Restore');
    }
}
