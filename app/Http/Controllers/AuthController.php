<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $register = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if ($register) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi Berhasil!',
                'data' => $register,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi Tidak Berhasil!',
                'data' => '',
            ], 400);
        }

    }

    public function login(Request $request)
    {
        //ambil data
        $email = $request->input('email');
        $password = $request->input('password');

        //cari datanyaa di tabel user
        $user = User::where('email', $email)->first();

        //validasi
        if (Hash::check($password, $user->password)) {
            $apiToken = base64_encode(Str::random(40));

            //update token
            $user->update([
                'api_token' => $apiToken,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil!',
                'data' => [
                    'user' => $user,
                    'api_token' => $apiToken,
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal!!',
                'data' => '',
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        // Mendapatkan user yang sedang login berdasarkan api_token
        $apiToken = explode(' ', $request->header('Authorization'));

        $user = User::where('api_token', $apiToken[1])->first();

        if ($user) {
            // Hapus api_token user
            $user->update(['api_token' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil!',
                'data' => ''
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal! Pengguna tidak ditemukan.',
                'data' => ''
            ], 400);
        }

    // return response()->json($user);
    }
}
