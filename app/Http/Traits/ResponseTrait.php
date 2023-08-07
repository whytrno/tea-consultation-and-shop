<?php

namespace App\Http\Traits;

trait ResponseTrait
{
    public function successResponse($data, $message = null, $code = 200)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function failedResponse($message = 'Terjadi kesalahan teknis', $code = 500)
    {
        $message = $message ?? 'Terjadi kesalahan teknis';
        return response([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    public function validationFailedResponse($errors, $message = null, $code = 422)
    {
        $message = $message ?? 'Validasi gagal, periksa kembali data anda';
        return response([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}