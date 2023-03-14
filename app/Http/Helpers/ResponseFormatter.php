<?php

namespace App\Http\Helpers;

class ResponseFormatter
{
    protected static $response = [
        'status' => '',
        'message' => '',
        'data' => null,
        'code' => '',
    ];

    public static function success($data = null, $message = 'Success', $code = 200)
    {
        self::$response['status'] = 'success';
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        self::$response['code'] = $code;

        return response()->json(self::$response, self::$response['code']);
    }

    public static function error($data = null, $message = 'Error', $code = 400)
    {
        self::$response['status'] = 'error';
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        self::$response['code'] = $code;

        return response()->json(self::$response, self::$response['code']);
    }
}
