<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User Ditemukan',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Tidak Ditemukan',
                'data' => ''
            ], 400);
        }
    }
}
