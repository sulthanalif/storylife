<?php

namespace App\Http\Controllers;

use App\Models\Service;
// use Faker\Provider\Image;
// use Illuminate\Auth\Events\Validated;
use App\Helpers\EncodeFile;
use Illuminate\Http\Request;

use App\Helpers\ResponseFormatter;
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
        $services = Service::all();

        if (isEmpty($services)) {
            return ResponseFormatter::error('', 'Belum Ada Data Pelayanan');
        }

        return ResponseFormatter::success($services, 'Data Berhasil Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $name = $request->input('name');
        $description = $request->input('description');
        $status_id = $request->input('status_id');

        $file = $request->file('image');
        $imageData = EncodeFile::encodeFile($file);
        $service = Service::create([
            'name' => $name,
            'description' => $description,
            'image' => $imageData,
            'status_id' => $status_id
        ]);

        if ($service) {
            return ResponseFormatter::success($service, 'Data Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'Data Gagal Disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}
