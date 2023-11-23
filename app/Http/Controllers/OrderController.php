<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Category;
use App\Models\StatusOrder;
use App\Models\User;

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
            'user_id'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
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
    public function destroy(Order $order)
    {
        //
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
