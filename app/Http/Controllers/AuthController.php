<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors()->first(), 400);
        }

        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ResponseFormatter::error('', 'Email atau Password Salah!', 401);
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terdapat kesalahan dalam sistem, silahkan coba lagi.', 500);
        }

        $user = User::where('email', $request->email)->first(['name', 'email']);

        return ResponseFormatter::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user 
        ], 'Login Berhasil!');
    }

    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken(); // Mendapatkan token dari permintaan
            JWTAuth::invalidate($token); // Mematikan token
    
            return ResponseFormatter::success('', 'Logout Berhasil');
        } catch (\Exception $exception) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan sistem', 500);
        }
    }

    public function getToken(Request $request)
    {
         // Dapatkan user yang di-authenticated saat ini
         $user = Auth::user();

         // Buat token untuk user
         $token = JWTAuth::fromUser($user);

        return ResponseFormatter::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], 'Token Berhasil Diperoleh!');
    }

    public function refreshToken(Request $request)
    {
        try {
            // Coba refresh token
            $token = JWTAuth::refresh(JWTAuth::getToken());

            // Dapatkan user terkini setelah refresh token
            $user = Auth::user();

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => $user,
            ], 'Token Berhasil Diperbaharui!');
        } catch (\Exception $e) {
            // Tangkap dan tangani eksepsi jika ada kesalahan dalam menyegarkan token
            return ResponseFormatter::error(null, 'Gagal menyegarkan token', 401);
        }
    }

    // public function login(Request $request)
    // {
    //     $rules = [
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);
    //     $credentials = $request->only(['email', 'password']);

    //     try {
    //         if ($validator->fails()) {
    //             return ResponseFormatter::error('', $validator->getMessageBag(), 400);
    //         }
    //         if (!$token = Auth::attempt($credentials)) {
    //             return response()->json(['message' => 'Email Atau Password Salah!!!'], 401);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Terdapat kesalahan dalam sistem, silahkan coba lagi.'], 500);
    //     }

    //     $user = User::where('email', $request->email)->first(['name', 'email']);

    //     return ResponseFormatter::success([
    //         'accessToken' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::factory()->getTTL() * 60,
    //         'user' => $user], 'Login Berhasil!');
    // }

    // public function logout(Request $request)
    // {
    //     auth()->logout();
    //     try {
    //         return ResponseFormatter::success('', 'Logout Berhasil');
    //     } catch (\Exception $exception) {
    //         return ResponseFormatter::error('', 'Terjadi Kesalaan sistem');

    //     }

    // }
}
