<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Contracts\Routing\ResponseFactory;

use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::all();

        if (isEmpty($statuses)){
            return ResponseFormatter::error('', 'Data Belum Ada');
        } 

        return ResponseFormatter::success($statuses, 'Data Ditemukan');
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
            'description' => 'string'
        ];

        //validasi inputan
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        //ambil data
        $name = $request->input('name');
        $description = $request->input('description');

        //simpan
        $simpan = Status::create([
            'name' => $name,
            'description' => $description
        ]);

        //cek
        if ($simpan) {
            return ResponseFormatter::success($simpan, 'Data Status Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'Data Status Gagal Disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cari
        $status = Status::find($id);

        if (!$status) {
            return ResponseFormatter::error('', 'Data Status Tidak Ditemukan');
        }

        return ResponseFormatter::success($status, 'Data Status Berhasil Ditemukan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //cari
        $status = Status::find($id);

        if (!$status) {
            return ResponseFormatter::error('', 'Data Status Tidak Ditemukan');
        }

        return ResponseFormatter::success($status, 'Data Status Berhasil Ditemukan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //rules
        $rules = [
            'name' => 'required|string',
            'description' => 'string'
        ];

        //validator
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $status = Status::find($id);

        if (!$status) {
            return ResponseFormatter::error('', 'Data Status Tidak Ditemukan');
        }

        $name = $request->input('name');
        $description = $request->input('description');

        //simpan
        $simpan = $status->update([
            'name' => $name,
            'description' => $description
        ]);

        if ($simpan) {
            return ResponseFormatter::success($simpan, 'Data Status Berhasil Diupdate');
        } else {
            return ResponseFormatter::error('', 'Data Status Gagal Diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cari data
        $status = Status::find($id);

        if ($status) {
            $status->delete();

            return ResponseFormatter::success('', 'Data Status Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Status Gagal Dihapus');
        }
    }
}
