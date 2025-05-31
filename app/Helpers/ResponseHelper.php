<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use PDO;

class ResponseHelper
{

    public static function sendError($message, $error = [], $code = Response::HTTP_UNAUTHORIZED)
    {
        $response = [
            'success' => false,
            'status' => $code,
            'message' => $message,
            'result' => (object)$error,
        ];

        throw new HttpResponseException(response()->json($response, $code));
    }

    public static function sendSuccess($message, $data = [], $code = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'status' => $code,
            'message' => $message,
            'result' => !empty($data) ? $data : (object)[],
        ];

        throw new HttpResponseException(response()->json($response, $code));
    }

    public static function sendForbidden($message, $error = [], $code = Response::HTTP_FORBIDDEN)
    {
        $response = [
            'success' => false,
            'status' => $code,
            'message' => $message,
            'result' => (object)$error,
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_OK));
    }
}
