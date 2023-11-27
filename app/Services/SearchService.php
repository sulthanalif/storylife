<?php

// app/Services/SearchService.php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class SearchService
{
    public function search($request, $model, $searchableColumns)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Ambil keyword dari permintaan
        $keyword = $request->input('keyword');

        // Lakukan pencarian berdasarkan keyword di kolom yang dapat dicari
        $searchResults = $model::where(function ($query) use ($searchableColumns, $keyword) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'like', "%$keyword%");
                }
            })->get();

        return $searchResults;
    }
}
