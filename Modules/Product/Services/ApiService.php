<?php

namespace Modules\Product\Services;

use App\Helpers\ResponseJson;
use Illuminate\Support\Facades\Http;
use Modules\Product\Repositories\Interface\ProductImageRepositoryInterface;

class ApiService
{
    public function callExternalApi($url)
    {
        $response = Http::get($url); 
        if ($response->successful()) {
            return $response->json(); 
        } else {
            return response()->json(['message' => 'Failed to fetch data from API'], $response->status());
        }
    }
}
