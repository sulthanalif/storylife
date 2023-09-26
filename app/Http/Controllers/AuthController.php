<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        // Menentukan aturan validasi
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 400);
        }


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
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $validator = Validator::make( $request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        //ambil data
        $email = $request->input('email');
        $password = $request->input('password');

        //cari datanyaa di tabel user
        $user = User::where('email', $email)->first();

        //validasi kalo salah email
        if ($user) {
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
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Salah',
                'data' => ''
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
