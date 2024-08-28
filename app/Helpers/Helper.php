<?php

namespace App\Helpers;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helper
{
    public static function getMemberType($user = null)
    {
        if(!isset($user))
        {
            $user = Auth::user();
        }
        return 'guest';
        return isset($user) ? "member_{$user->member_type}" : 'guest';
    }
    public static function DBTransaction(Closure $tryCallBack, Closure $catchCallBack = null)
    {
        DB::beginTransaction();
        try {
            $data = $tryCallBack();
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            if (isset($catchCallBack)) {
                return $catchCallBack($e);
            }
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    // public static function leftMasked($value, int $length = null, string $separator = '')
    // {
    //     if (!isset($length)) {
    //         $length = strlen($value);
    //     }
    //     $secondPart = '';
    //     if (isset($separator) && $separator != '') {
    //         $parts = explode($separator, $value);
    //         $value = $parts[1];
    //         $secondPart = $parts[0];
    //     }
    //     if (strlen($value) >= $length) {
    //         $firstPartMarked = substr($value, 0, $length);
    //         $lastPartMarked = substr($value, $length);
    //         $c = str_repeat('*', strlen($firstPartMarked));
    //         return "{$secondPart}{$separator}{$c}{$lastPartMarked}";
    //     }
    //     $c = str_repeat('*', strlen($value));
    //     return "{$secondPart}{$separator}{$c}";
    // }
    // public static function rightMasked($value, int $length = null, string $separator = '')
    // {
    //     if (!isset($length)) {
    //         $length = strlen($value);
    //     }
    //     $secondPart = '';
    //     if (isset($separator) && $separator != '') {
    //         $parts = explode($separator, $value);
    //         $value = $parts[0];
    //         $secondPart = $parts[1];
    //     }
    //     if (strlen($value) >= $length) {
    //         $firstPartMarked = substr($value, -$length);
    //         $lastPartMarked = substr($value, 0, $length);
    //         $c = str_repeat('*', strlen($firstPartMarked));
    //         return "{$secondPart}{$separator}{$c}{$lastPartMarked}";
    //     }
    //     $c = str_repeat('*', strlen($value));
    //     return "{$secondPart}{$separator}{$c}";
    // }

    public static function subMasked(string $value = null, int $offset = null, int|string $separator = null)
    {
        if (!isset($value)) {
            return '*';
        }
        $lengthValue = strlen($value);
        $isRevers = false;
        if (!isset($offset)) {
            $offset = 0;
        } elseif ($offset < 0) {
            $isRevers = true;
            $offset = -$offset;
            $value = strrev($value);
        }
        $length = null;
        $secondPart = '';
        if ($separator === null) {
            $length = $lengthValue;
            $separator = '';
        } elseif (is_string($separator)) {
            if (strpos($value, $separator) == false) {
                throw new Exception("In string '{$value}' not found '{$separator}'");
            }
            $parts = explode($separator, $value, 2);
            $length = strlen($parts[0]) - $offset;
            $secondPart = $parts[1];
        } else {
            $length = $separator;
            if ($length > $lengthValue) {
                $length = $lengthValue;
            }
            $separator = substr($value, $offset + $length);
        }

        if ($offset >= $length) {
            $offset = 0;
            // throw new Exception('Offset must be less than length');
        }
        $encoding = 'UTF-8';
        if (!mb_check_encoding($value, 'UTF-8')) {
            $encoding = mb_detect_encoding($value, mb_list_encodings(), true);
            if (!isset($encoding)) {
                // $firstPartMarked = substr($value, 0, $offset);
                throw new Exception("Unable to determine encoding of string '{$value}'");
            }
        }

        $firstPartMarked = mb_substr($value, 0, $offset, $encoding);
        // $firstPartMarked = substr($value, 0, $offset);
        $c = str_repeat('*', $length);
        $result = "{$firstPartMarked}{$c}{$separator}{$secondPart}";
        if ($isRevers) {
            return strrev($result);
        }
        return $result;
    }
    public static function containsSpecialCharacters(string $string, string $exception = '')
    {
        try {
            $escapedException = preg_quote($exception, '/');
            $pattern = "/[^a-zA-Z0-9{$escapedException}]/";
            if (preg_match($pattern, $string)) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
    public static function randomOTP($length = 6)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function randomOTPNumeric($length = 6)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function calculatePrice($baseProduct)
    {
        $price = $baseProduct->price - ($baseProduct->price * $baseProduct->discount);
        return round($price, 2);
    }
    public static function formatFloat($value, $precision = 0)
    {
        return round($value, $precision);
    }
    public static function getIsFlashSale($baseProduct)
    {
        if (!isset($baseProduct->is_flash_sale)) {
            $baseProduct->is_flash_sale = isset($baseProduct->product_flash_sale_start_time) && isset($baseProduct->product_flash_sale_end_time);
        }
        return $baseProduct->is_flash_sale;
    }
    public static function getPriceNew($baseProduct): float
    {
        if (!isset($baseProduct->price_new)) {
            $discountNew = self::getDiscountNew($baseProduct);
            $baseProduct->price_new = $baseProduct->price * (1 - $discountNew);
        }
        return $baseProduct->price_new;
    }
    public static function getDiscountNew($baseProduct): float
    {
        $isFlashSale = self::getIsFlashSale($baseProduct);
        if (!isset($baseProduct->discount_new)) {
            // $this->discountNew = Helper::calculatePriceNew($baseProduct);
            $baseProduct->discount_new = $baseProduct->product_discount;
            if ($isFlashSale) {
                $baseProduct->discount_new = min($baseProduct->discount_new + $baseProduct->product_flash_sale_discount, 1);
            }
        }
        return $baseProduct->discount_new;
    }
    public static function saveImage($imageUrl, $filePath)
    {
        try {
            $response = Http::get($imageUrl);
            if ($response->ok()) {
                // Log::info($response->body());
                $data = $response->body();
                // $mimeType = str_replace('data:', '', $data[0]);
                // $base64Data = $data[1];
                // $imageData = base64_decode($base64Data);
                // file_put_contents($filePath, $imageData);
                Storage::disk('public')->put($filePath, $data);
                // Storage::put($filePath, $imageData);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
