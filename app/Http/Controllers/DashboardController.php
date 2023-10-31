<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index () {
        $cek = [
            'reviews' => Review::all()->count(),
        ];

        if (! $cek) {
            return ResponseFormatter::error('', 'Error');
        }

        return ResponseFormatter::success( $cek, 'Success');
        
    }
}
