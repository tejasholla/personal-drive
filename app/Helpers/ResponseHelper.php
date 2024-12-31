<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function json(string $message, bool $ok = true): JsonResponse
    {
        return response()->json([
            'ok' => $ok,
            'message' => $message
        ], $ok ? 200 : 400);
    }

}
