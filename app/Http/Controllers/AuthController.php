<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;


class AuthController extends Controller
{
    public function register(Request $request) 
    {
        // Menentukan aturan validasi
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'konfirm_pass' => 'required|string',
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            return ResponseFormatter::error('', 'Email sudah terpakai', 400);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        
        //cek apakan password sama dengan konfirmasi password
        if ($request->input('password') != $request->input('konfirm_pass')) {
            return ResponseFormatter::error('', 'Password dan Konfirmasi Password harus sama!', 400);
        }
        
        $password = Hash::make($request->input('password'));

        $register = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if ($register) {
            return ResponseFormatter::success($register, 'Registrasi Berhasil');
            } else {
            return ResponseFormatter::error('', 'Registrasi Tidak Berhasil');
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
            return ResponseFormatter::error('', 'Validasi Gagal!', 400);
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
                return ResponseFormatter::success(['user' => $user, 'api_token' =>  $apiToken], 'Login Berhasil!');

            } else {
                return ResponseFormatter::error('', 'Login Gagal!');
            }
        } else {
            return ResponseFormatter::error('', 'Email atau Password Salah');
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

            return ResponseFormatter::success($user, 'Logout Berhasil');
        } else {
            return ResponseFormatter::error('', 'Logout Gagal');
        }

    }
}
