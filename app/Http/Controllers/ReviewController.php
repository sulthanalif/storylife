<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
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
        $reviews = Review::all();

        if ($reviews->isEmpty()) {
            return ResponseFormatter::error('', 'Tidak Ada Data');
        }

        return ResponseFormatter::success($reviews, 'Data Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ResponseFormatter::success('', 'Tampilan Bikin Review');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //membuat rules
        $rules = [
            'category_id' => 'required',
            'rating' => 'required',
            'comment' => 'required|string'
        ];

        //validasi inputan
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        //cari user yg sedang komen
        $apiToken = explode(' ', $request->header('Authorization'));

        $user = User::where('api_token', $apiToken[1])->first();

        //ambil data form
        $category_id = $request->input('category_id');
        $rating = $request->input('rating');
        $comment = $request->input('comment');

        //simpan data
        $simpan = Review::create([
            'user_id' => $user->id,
            'category_id' => $category_id,
            'rating' => $rating,
            'comment' => $comment
        ]);

        //cek simpan
        if ($simpan) {
            return ResponseFormatter::success($simpan, 'Review Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'Review Gagal Disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //cari data
        $review = Review::find($id);

        if (!$review) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        return ResponseFormatter::success($review, 'Data Ditemukan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //cari data
        $review = Review::find($id);

        if (!$review) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        return ResponseFormatter::success($review, 'Data Ditemukan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //membuat rules
        $rules = [
            'category_id' => 'required',
            'rating' => 'required',
            'comment' => 'required|string'
        ];

        //validasi inputan
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        //cari user yg sedang komen
        // $apiToken = explode(' ', $request->header('Authorization'));

        // $user = User::where('api_token', $apiToken[1])->first();

        //cari review yg mau diedit
        $review = Review::find($id);

        if (!$review) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        //ambil data form
        $category_id = $request->input('category_id');
        $rating = $request->input('rating');
        $comment = $request->input('comment');

        //simpan data
        $simpan = $review->update([
            // 'user_id' => $user->id,
            'category_id' => $category_id,
            'rating' => $rating,
            'comment' => $comment
        ]);

        //cek simpan
        if ($simpan) {
            return ResponseFormatter::success($review, 'Review Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'Review Gagal Disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cari data
        $review = Review::find($id);

        if ($review) {
            $review->delete();

            return ResponseFormatter::success('', 'Review Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
    }

    public function getList(Request $request) {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        if ($perPage === "bypass") {
            $reviews = Review::with('user', 'category')->get();
            $total = $reviews->count();
            $data = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user->name,
                    'category' => $review->category->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment
                ];
            });
        } else {
            $paginator = Review::with('user', 'category')->paginate($perPage, ['*'], 'page', $page);
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
        ], 'Berhasil Menampilkan Data Review');
    }
}
