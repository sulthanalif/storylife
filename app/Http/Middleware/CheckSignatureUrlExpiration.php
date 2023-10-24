<?php

namespace App\Http\Middleware;

use Closure;

class CheckSignatureUrlExpiration
{
    public function handle($request, Closure $next)
    {
        $expires = $request->input('expires');

        if (!$expires) {
            return response()->json(['error' => 'URL tidak memiliki waktu kadaluwarsa'], 400);
        }

        $currentTimestamp = time();
        $expirationTimestamp = $expires;

        if ($currentTimestamp > $expirationTimestamp) {
            return response()->json(['error' => 'URL telah kedaluwarsa'], 403);
        }

        // Batasan waktu kadaluwarsa: 3 hari (259200 detik)
        // $expirationLimit = 259200;
        $expirationLimit = 10;

        if (($expirationTimestamp - $currentTimestamp) > $expirationLimit) {
            return response()->json(['error' => 'URL memiliki batasan waktu lebih dari 3 hari'], 403);
        }

        // Lanjutkan pemrosesan jika URL masih berlaku
        return $next($request);
    }
}
