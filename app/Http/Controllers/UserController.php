<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return ResponseFormatter::error('', 'Belum ada data user');
        }

        return ResponseFormatter::success($users, 'Data Berhasil Ditemukan');
    }

    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        } 

        return ResponseFormatter::success($user, 'Data Ditemukan!');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        if ($user) {
            return ResponseFormatter::success($user, 'User Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'User Gagal Disimpan');
        }
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        return ResponseFormatter::success($user, 'Data Berhasil Ditemukan');
    }

    public function updateProfile(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $name = $request->input('name');
        $email = $request->input('email');
        // $password = Hash::make($request->input('password'));

        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'Data User Tidak Ditemukan');
        }

        $updateprofile = $user->update([
            'name' => $name,
            'email' => $email,
            // 'password' => $password
        ]);

        if ($updateprofile) {
            return ResponseFormatter::success($user, 'Update Profile Berhasil Disimpan');
        } else {
            return ResponseFormatter::error('', 'Update Profile Gagal Disimpan');
        }
    }

    public function updatePass(Request $request, $id)
    {
        $rules = [
            'passLama' => 'required|string',
            'passBaru' => 'required|string',
            'konfirmPass' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $user = User::find($id);
        $userPass = $user->password;
        $passLama = $request->input('passLama');
        $passBaru = $request->input('passBaru');
        $konfirmPass = $request->input('konfirmPass');
        
        if ($userPass == $passLama) {
            if ($passBaru == $konfirmPass) {
                $updatePass = $user->update([
                    'password' => $passBaru
                ]);

                if ($updatePass) {
                    return ResponseFormatter::success('', 'Password Baru Berhasil Disimpan');
                } else {
                    return ResponseFormatter::error('', 'Password Baru Gagal Disimpan!');
                }
            } else {
                return ResponseFormatter::error('', 'Password Baru dan Konfirmasi Password Harus Sama!');
            }
        } else {
            return ResponseFormatter::error('', 'Password Lama Salah!');
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'User Tidak Ditemukan');
        }

        return ResponseFormatter::success('', 'Data User Berhasil Dihapus');
    }
}
