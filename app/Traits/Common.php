<?php

namespace App\Traits;

trait Common
{
    public static function response($data, $msg, $code)
    {
        $response = [
            'status' => $code == 200 ? true : false,
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($response, $code);
    }

    public static function success($data = [], $msg = 'Success', $code = 200)
    {
        return self::response($data, $msg, $code);
    }

    public static function fail($data = [], $msg = "Some thing wen't wrong!", $code = 400)
    {
        return self::response($data, $msg, $code);
    }
}
