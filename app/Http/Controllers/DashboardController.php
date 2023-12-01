<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index () {
        $order_success = Order::with('category', 'user', 'statusOrder')
        ->whereHas('statusOrder', function ($query) {
            $query->where('name', 'Success');
        })->get();
        $order_pending = Order::with('category', 'user', 'statusOrder')
        ->whereHas('statusOrder', function ($query) {
            $query->where('name', 'Pending');
        })->get();

        $reviews = Review::with('category')->get();

        $stat = [
            'Review' => $reviews->count(),
            'Order_Success' => $order_success->count(),
            'Order_Pending' => $order_pending->count(),
        ];

        $pesananBaruSelesai = $order_success->map(function ($order) {
            return [
                'id' =>$order->id,
                'user' => $order->user->name,
                'category' => $order->category->name,
                'status_order' => $order->statusOrder->name,
                'booked_at' => $order->booked_at,
                'order_at' => $order->created_at,
                'price' => $order->price
            ];
        })->take(5);

        $review = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user' => $review->user->name,
                'category' => $review->category->name,
                'rating' => (int)$review->rating,
                'comment' => $review->comment
            ];
        })->take(5);
       

        return ResponseFormatter::success([
            'stat' => $stat,
            'pesananBaruSelesai' => $pesananBaruSelesai,
            'review' => $review
        ], 'Success yah :)');
        
    }
}
