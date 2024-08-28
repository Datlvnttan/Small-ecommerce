<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class ResponseJson
{
    public static function setup(?bool $success = null, ?string $msg = null, ?int $status = null, mixed $data = null, $errors = null, $error = null, $statusCode = Response::HTTP_OK)
    {
        $json = [];
        if (isset($success))
            $json["success"] = $success;
        if (isset($msg))
            $json["message"] = $msg;
        if (isset($status))
            $json["status"] = $status;
        if (isset($data))
            $json["data"] = $data;
        if (isset($errors))
            $json["errors"] = $errors;
        if (isset($error))
            $json["error"] = $error;
        return response()->json($json, $statusCode);
    }
    public static function successSetup(?bool $success = true, string $msg = null, mixed $data = null, ?int $statusCode = 500)
    {
        return ResponseJson::setup(success: $success, msg: $msg, data: $data, statusCode: $statusCode);
    }
    public static function success(string $msg = null, mixed $data = null)
    {
        return ResponseJson::setup(success: true, msg: $msg, data: $data);
    }
    public static function failed(string $msg = null, $statusCode = 400)
    {
        return ResponseJson::setup(success: false, msg: $msg, statusCode: $statusCode);
    }
    public static function error($error = null, $statusCode = 500)
    {
        return ResponseJson::setup(success: false, error: $error, statusCode: $statusCode);
    }
    public static function errors($errors = null, $statusCode = 500)
    {
        return ResponseJson::setup(success: false, errors: $errors, statusCode: $statusCode);
    }
    public static function status(?int $status = 1, ?string $msg = null, mixed $data = null, ?int $statusCode = 500)
    {
        return ResponseJson::setup(status: $status, msg: $msg, data: $data, statusCode: $statusCode);
    }
    public static function isHttpErrorCode($code)
    {
        if (is_int($code)) {
            return ($code >= 400 && $code < 600);
        }
        return false;
    }
}
