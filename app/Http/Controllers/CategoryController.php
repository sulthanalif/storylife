<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ambil data category
        $category = Category::all();

        //kalo kosong
        if ($category->isEmpty()) {
            return ResponseFormatter::error('', 'Tidak Ada Data');
        } else {
            return ResponseFormatter::success($category, 'Berhasil Menampilkan Data!');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ResponseFormatter::success('', 'Halaman Create Category');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //rules
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
        ];

        //validasi data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', 'Validasi Gagal');
        }

        //ambil data
        $name = $request->input('name');
        $description = $request->input('description');

        //tambah data
        $category = Category::create([
            'name' => $name,
            'description' => $description
        ]);

        //validasi proses tambah
        if ($category) {
            return ResponseFormatter::success($category, 'Data Berhasil Ditambah');
        } else {
            return ResponseFormatter::error('', 'Gagal Menambah Data');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if ($category) {
            return ResponseFormatter::success($category, 'Data Berhasil Ditemukan!');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Dapat Ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        if ($category) {
            return ResponseFormatter::success($category, 'Data Berhasil Ditemukan!');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Dapat Ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //rules
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
        ];

        //validasi data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', 'Validasi Gagal');
        }

        //ambil data
        $name = $request->input('name');
        $description = $request->input('description');

        //cari data yg mau di update
        $category = Category::find($id);

        if ($category) {
            //tambah data
            $update = $category->update([
                'name' => $name,
                'description' => $description
            ]);

            //validasi proses tambah
            if ($update) {
                return ResponseFormatter::success($category, 'Data Berhasil Ditambah');
            } else {
                return ResponseFormatter::error('', 'Gagal Menambah Data');
            }
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();

            return ResponseFormatter::success('', 'Data Category Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
    }
}
