<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\StatusOrder;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();

        return ResponseFormatter::success($orders, 'Success');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $users = User::select('id', 'name', 'email')->get();
            $categories = Category::select('id', 'name')->get();
            $statusOrders = StatusOrder::select('id', 'name')->get();

            if($users && $categories && $statusOrders) {
                return ResponseFormatter::success([
                    'user' => $users,
                    'category' => $categories,
                    'statusOrder' => $statusOrders
                ], 'Success menampilkan form');
            } else {
                return ResponseFormatter::error('', 'Ada Yang Error');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Pada Sistem');
        }
        
        // return ResponseFormatter::success('','Form Order');
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
            'user_id' => 'required',
            'category_id' => 'required',
            'status_id' => 'required',
            'booked_at' => 'required',
            'note' => 'required',
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$order) {
                $user = $request->input('user_id');
                $category = $request->input('category_id');
                $statusOrder = $request->input('status_id');
                $booked_at = $request->input('booked_at');
                $note = $request->input('note');
                $price = $request->input('price');

                $order = Order::create([
                    'user_id' => $user,
                    'category_id' => $category,
                    'status_id' => $statusOrder,
                    'booked_at' => $booked_at,
                    'note' => $note,
                    'price' => $price,
                ]);
            });
            if ($order) {
                return ResponseFormatter::success($order, 'Data Berhasil Ditambahkan');
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->input('id');
        $order = Order::with('user', 'category', 'statusOrder')->where('id', $id)->first();

        if($order) {
            $data = [
                'id' => $order->id,
                    'user' => $order->user->email,
                    'category' => $order->category->name,
                    'status_order' => $order->statusOrder->name,
                    'booked_at' => $order->booked_at,
                    'note' => $order->note,
                    'price' => $order->price
            ];
            return ResponseFormatter::success($data, 'Succeess Menampilkan Data');
        } else {
            return ResponseFormatter::error('', 'Gagal Menampilkan Data');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {  
        $id = $request->input('id');

        try {
            $order = Order::where('id', $id)->first();
            $users = User::select('id', 'name', 'email')->get();
            $categories = Category::select('id', 'name')->get();
            $statusOrders = StatusOrder::select('id', 'name')->get();

            if($order && $users && $categories && $statusOrders) {
                return ResponseFormatter::success([
                    'order' => $order,
                    'user' => $users,
                    'category' => $categories,
                    'statusOrder' => $statusOrders
                ], 'Success menampilkan form Edit');
            } else {
                return ResponseFormatter::error('', 'Ada Yang Error');
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Pada Sistem');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $order = Order::find($request->input('id'));

        if ($order) {
            $order->delete();

            return ResponseFormatter::success('', 'Data Berhasil Dihapus');
        } else {
            return ResponseFormatter::error('', 'Gagal Menghapus Data');
        }
    } 

    public function getList(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        if ($perPage === 'bypass' || $page === 'bypass') {
            // Jika per_page bernilai "bypass", gunakan metode bypass
            $orders = Order::with('user', 'category', 'statusOrder')->get();
            $total = $orders->count();
            $data = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'user' => $order->user->email,
                    'category' => $order->category->name,
                    'status_order' => $order->statusOrder->name,
                    'booked_at' => $order->booked_at,
                    'note' => $order->note,
                    'price' => $order->price
                ];
            });
        } else {
            // Jika per_page memiliki nilai selain "bypass", gunakan paginasi
            $paginator = order::with('user', 'category', 'statusOrder')->paginate($perPage, ['*'], 'page', $page);
            $orders = $paginator->items();
            $data = collect($orders)->map(function ($order) {
                return [
                    'id' => $order->id,
                    'user' => $order->user->email,
                    'category' => $order->category->name,
                    'status_order' => $order->statusOrder->name,
                    'booked_at' => $order->booked_at,
                    'note' => $order->note,
                    'price' => $order->price
                ];
            });
            $total = $paginator->total();
        }

        //URL
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
        ], 'Berhasil Menampilkan Data Order');
    }
}
