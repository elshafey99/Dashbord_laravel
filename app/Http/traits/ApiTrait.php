<?php

namespace App\Http\traits;


trait ApiTrait
{
    public function SuccessMessage(string $message = "", int $code = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'error' => (object)[],
                'data' => (object)[]
            ],
            $code
        );
    }

    public function ErrorMessage(array $error, string $message = "", int $code = 422)
    {
        return response()->json(
            [
                'message' => $message,
                'error' => $error,
                'data' => (object)[]
            ],
            $code
        );
    }

    public function Data(array $data, string $message = "", int $code = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'error' => (object)[],
                'data' => $data
            ]
        );
    }
}