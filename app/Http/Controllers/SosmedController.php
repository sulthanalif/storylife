<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Sosmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SosmedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sosmed = Sosmed::all();

        if ($sosmed->isEmpty()) {
            return ResponseFormatter::error('', 'Belum Ada Data');
        }

        return ResponseFormatter::success($sosmed, 'Data Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ResponseFormatter::success('', 'Halaman Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'link' => 'required',
            'icon' => 'required',
            'status_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $name = $request->input('name');
        $link = $request->input('link');
        $icon = $request->input('icon');
        $status_id = $request->input('status_id');

        $sosmed = Sosmed::create([
            'name' => $name,
            'link' => $link,
            'icon' => $icon,
            'status_id' => $status_id
        ]);

        if (!$sosmed) {
            return ResponseFormatter::error('', 'Gagal Menyimpan Data');
        }

        return ResponseFormatter::success($sosmed, 'Berhasil Menyimpan Data');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sosmed  $sosmed
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sosmed = Sosmed::find($id);

        if (!$sosmed) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        return ResponseFormatter::success($sosmed, 'Data Ditemukan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sosmed  $sosmed
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sosmed = Sosmed::find($id);

        if (!$sosmed) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        return ResponseFormatter::success($sosmed, 'Data Ditemukan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sosmed  $sosmed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'link' => 'required',
            'icon' => 'required',
            'status_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $name = $request->input('name');
        $link = $request->input('link');
        $icon = $request->input('icon');
        $status_id = $request->input('status_id');

        $sosmed = Sosmed::find($id);
        $update = $sosmed->update([
            'name' => $name,
            'link' => $link,
            'icon' => $icon,
            'status_id' => $status_id
        ]);

        if (!$update) {
            return ResponseFormatter::error('', 'Gagal Mengupdate Data');
        }

        return ResponseFormatter::success($sosmed, 'Berhasil Mengupdate Data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sosmed  $sosmed
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sosmed = Sosmed::find($id);

        if($sosmed) {
            $sosmed->delete();

            return ResponseFormatter::success('', 'Data Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Gagal Dihapus');
        }
    }

    public function restore($id) 
    {
        $data = Sosmed::withTrashed()->where('id', $id)->first();
        $restore = $data->restore();

        if (!$restore) {
            return ResponseFormatter::error('', 'Gagal Restore Data');
        }

        return ResponseFormatter::success('', 'Berhasil Restore');
    } 
}
