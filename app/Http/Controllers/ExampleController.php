<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function form()
    {
        $expirationTimestamp = time() + 10; // 10 detik dari sekarang
        $url = app('signature-url')->generateSignatureUrl('/form', ['expires' => $expirationTimestamp]);
    
        return ResponseFormatter::success($url, 'Masukkkk');
    }
}
