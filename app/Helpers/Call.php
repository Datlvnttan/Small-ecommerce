<?php
namespace App\Helpers;

use Closure;
use Exception;
use Illuminate\Http\Response;
use League\Fractal\Manager;

class Call
{
    public static function TryCatchResponseJson(Closure $callBack) 
    {
        try
        {
            return $callBack();
        }
        catch(Exception $e)
        {
            $statusCode = $e->getCode();
            if(!ResponseJson::isHttpErrorCode($statusCode))
            {
                $statusCode = 500;
            }
            return ResponseJson::error($e->getMessage(), $statusCode);
        }
    }
    public static function SafeExecute(Closure $callBack,$title = 'Error! An error occurred.') 
    {
        try
        {
            return $callBack();
        }
        catch(Exception $e)
        {
            return view('errors.404',[
                'error' => $e->getMessage(),
                'title' => $title 
            ]);
        }
    }
    public static function SafeExecuteRenderError(Closure $callBack,$title = 'Error! An error occurred.') 
    {
        try
        {
            return $callBack();
        }
        catch(Exception $e)
        {
            return view('errors.404',[
                'error' => $e->getMessage(),
                'title' => $title 
            ])->render();
        }
    }
    public static function TryCatchResponseJsonFractalManager(Closure $callBack) 
    {
        try
        {
            $fractal = new Manager();
            return $callBack($fractal);
        }
        catch(Exception $e)
        {
            return ResponseJson::error($e->getMessage());
        }
    }
}


