<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return ResponseFormatter::error('', 'Belum ada data user');
        }

        return ResponseFormatter::success($users, 'Data Berhasil Ditemukan');
    }

    public function show(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }
        
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        return ResponseFormatter::success($data, 'Data Ditemukan!');
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

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'Data Tidak Ditemukan');
        }

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        return ResponseFormatter::success($data, 'Data Berhasil Ditemukan');
    }

    public function updateProfile(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        try {
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
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
        }
    }

    public function updatePass(Request $request)
    {
        $id = $request->input('id');
        $rules = [
            'passLama' => 'required|string',
            'passBaru' => 'required|string',
            'konfirmPass' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        try {
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
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Terjadi Kesalahan Sistem');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);

        if (is_null($user)) {
            return ResponseFormatter::error('', 'User Tidak Ditemukan');
        }

        $user->delete();
        return ResponseFormatter::success('', 'Data User Berhasil Dihapus');
    }

    public function trash()
    {
        $users = User::onlyTrashed()->get();

        if (!$users) {
            return ResponseFormatter::success('', 'Tidak Ada Data');
        }

        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        });

        if (isEmpty($data)) {
            return ResponseFormatter::success('', 'Trash Kosong');
        }

        return ResponseFormatter::success($data, 'Trash');
    }

    public function restore(Request $request) 
    {
        $id = $request->input('id');
    
        try {
            $data = User::withTrashed()->where('id', $id)->first();

            if (!$data) {
                return ResponseFormatter::error('', 'Data tidak ditemukan');
            }

            // Lakukan restore
            $data->restore();

            return ResponseFormatter::success('', 'Berhasil Restore');
        } catch (\Exception $e) {
            return ResponseFormatter::error('', 'Gagal Restore Data');
        }
    }

    public function getList(Request $request) 
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        if ($perPage === "bypass" || $page === "bypass") {
            $users = User::get();
            $total = $users->count();
            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            });
        } else {
            $paginator = User::paginate($perPage, ['*'], 'page', $page);
            $users = $paginator->items();
            $data = collect($users)->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            });
            $total = $paginator->total();
        }

        $nextPageUrl = $perPage === 'bypass' || $page === "bypass" ? null : PaginationHelper::getNextPageUrl($request, $page, $perPage, $total);
        $prevPageUrl = $perPage === 'bypass' || $page === "bypass" ? null : PaginationHelper::getPrevPageUrl($request, $page, $perPage);

        return ResponseFormatter::success([
            'current_page' => (int)$page,
            'data' => $data,
            'next_page_url' => $nextPageUrl,
            'path' => $request->url(),
            'per_page' => (int)$perPage,
            'prev_page_url' => $prevPageUrl,
            'to' => (int)$page * (int)$perPage,
            'total' => (int)$total,
        ], 'Berhasil Menampilkan Data user');
    }
}
