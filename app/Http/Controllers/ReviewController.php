<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Helpers\PaginationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Status;
use Illuminate\Contracts\Routing\ResponseFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $request)
    {
        $searchableColumns = ['category_id', 'rating', 'comment'];
        $searchResults = $this->searchService->search($request, Review::class, $searchableColumns);

        if (!$searchResults) {
            return ResponseFormatter::error('', 'Gagal Mencari Data');
        }
        return ResponseFormatter::success(collect($searchResults), 'Hasil Pencarian');
    }

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
        $categories = Category::get();

        if (!$categories) {
            return ResponseFormatter::error('', 'Categories Tidak Ada Data!');
        }

        $category = $categories->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
                'description' => $data->description
            ];
        });
        return ResponseFormatter::success($category, 'Tampilan Bikin Review');
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

       

        $user = JWTAuth::parseToken()->authenticate();
        $status = Status::where('name', 'Pending')->first();
        //ambil data form
        $category_id = $request->input('category_id');
        $rating = $request->input('rating');
        $comment = $request->input('comment');

        //simpan data
        $simpan = Review::create([
            'user_id' => $user->id,
            'category_id' => $category_id,
            'status_id' => $status->id,
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
    public function show(Request $request)
    {
        //cari data
        $id = $request->input('id');
        $review = Review::with('category', 'user', 'status')->where('id', $id)->first();

        if (!$review) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        $data = [
            'id' => $review->id,
            'user' => $review->user->name,
            'category' => $review->category->name,
            'status' => $review->status->name,
            'rating' => (int)$review->rating,
            'comment' => $review->comment
        ];
        return ResponseFormatter::success($data, 'Data Ditemukan');
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
    public function destroy(Request $request)
    {
        //cari data
        $id = $request->input('id');
        $review = Review::find($id);

        if ($review) {
            $review->delete();

            return ResponseFormatter::success('', 'Review Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
    }

    public function approve(Request $request) 
    {
        try {
            $id = $request->input('id');
            $review = Review::with('category', 'user', 'status')->where('id', $id)->first();
            $status = Status::where('name', 'Active')->first();
            $data = [
                'id' => $review->id,
                'user' => $review->user->name,
                'category' => $review->category->name,
                'status' => $review->status->name,
                'rating' => (int)$review->rating,
                'comment' => $review->comment
            ];

            if ($review->status->name === "Active") {
                return ResponseFormatter::success($data, 'Data Sudah Active');
            } else {
                if ($review) {
                    //simpan data
                    $simpan = $review->update([
                    'status_id' => $status->id,
                    ]);
    
                    if ($status) {
                        return ResponseFormatter::success($simpan, 'Review Approved');
                    } else {
                        return ResponseFormatter::error('', 'Review Gagal Approved');
                    }
                }
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
        }
    }

    public function reject(Request $request) 
    {
        try {
            $id = $request->input('id');
            $review = Review::with('category', 'user', 'status')->where('id', $id)->first();
            $status = Status::where('name', 'Draft')->first();
            $data = [
                'id' => $review->id,
                'user' => $review->user->name,
                'category' => $review->category->name,
                'status' => $review->status->name,
                'rating' => (int)$review->rating,
                'comment' => $review->comment
            ];

            if ($review->status->name === "Draft") {
                return ResponseFormatter::success($data, 'Data Sudah Reject masuk ke draft');
            } else {
                if ($review) {
                    //simpan data
                    $simpan = $review->update([
                    'status_id' => $status->id,
                    ]);
    
                    if ($status) {
                        return ResponseFormatter::success($simpan, 'Review Rejected');
                    } else {
                        return ResponseFormatter::error('', 'Review Gagal Rejected');
                    }
                }
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
        }
    }

    public function getList(Request $request) {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        if ($perPage === "bypass" || $page === "bypass") {
            $reviews = Review::with('user', 'category')->get();
            $total = $reviews->count();
            $data = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user->name,
                    'category' => $review->category->name,
                    'status' => $review->status->name,
                    'rating' => (int)$review->rating,
                    'comment' => $review->comment
                ];
            });
        } else {
            $paginator = Review::with('user', 'category')->paginate($perPage, ['*'], 'page', $page);
            $reviews = $paginator->items();
            $data = collect($reviews)->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user->name,
                    'category' => $review->category->name,
                    'status' => $review->status->name,
                    'rating' => (int)$review->rating,
                    'comment' => $review->comment
                ];
            });
            $total = $paginator->total();
        }

        $nextPageUrl = $perPage === 'bypass' || $page === "bypass" ? null : PaginationHelper::getNextPageUrl($request, $page, $perPage, $total);
        $prevPageUrl = $perPage === 'bypass' || $page === "bypass" ? null : PaginationHelper::getPrevPageUrl($request, $page, $perPage);

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
