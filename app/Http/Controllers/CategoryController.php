<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
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
        $categories = Category::all();

        $data = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description
            ];
        });

        //kalo kosong
        if ($data->isEmpty()) {
            return ResponseFormatter::error('', 'Tidak Ada Data');
        } else {
            return ResponseFormatter::success($data, 'Berhasil Menampilkan Data!');
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

        try {
            DB::transaction(function () use ($request, &$categories) {
                //ambil data
                $name = $request->input('name');
                $description = $request->input('description');
                
                //tambah data
                $categories = Category::create([
                    'name' => $name,
                    'description' => $description
                ]);
            });

            if($categories) {
                return ResponseFormatter::success($categories, 'Data Berhasil Disimpan');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Disimpan');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Pada Sistem');
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
        $category = Category::where('id',$id)->select('id', 'name', 'description')->first();

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
        $category = Category::where('id',$id)->select('id', 'name', 'description')->first();

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

        try {
            DB::transaction(function () use ($request, $id, &$categories, &$update) {
                //ambil data
                $name = $request->input('name');
                $description = $request->input('description');
                
                //cari data yg mau di update
                $categories = Category::find($id);

                 //tambah data
                $update = $categories->update([
                'name' => $name,
                'description' => $description
                ]);
            });

            if($update) {
                return ResponseFormatter::success($categories, 'Data Berhasil Disimpan');
            } else {
                return ResponseFormatter::error('', 'Data Gagal Disimpan');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Pada Sistem');
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
        $category = Category::where($id);

        if ($category) {
            $category->delete();

            return ResponseFormatter::success('', 'Data Category Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
    }
}
