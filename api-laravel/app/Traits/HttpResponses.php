<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

trait HttpResponses
{
    public static function response(string $message, string|int $status, array|Model|JsonResource $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data,
        ], $status);
    }

    public static function error(string $message, string|int $status, array $errors = [], array|Model $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'error' => $errors,
            'data' => $data,
        ], $status);
    }

}
