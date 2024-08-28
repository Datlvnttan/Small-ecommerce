<?php

namespace Modules\External\Services;

use Illuminate\Support\Facades\Http;

class GeoapifyExternalApiService extends BaseExternalApiService
{
    protected $apiKey;
    public const IP_GEOLOCATION_API = 'ipinfo';
    public const VERSION = 'v1';
    public function __construct()
    {
        parent::__construct();
        $this->apiKey = config('geoapify.api_key');
    }
    protected function getBaseUrl()
    {
        return config('geoapify.base_url');
    }
    protected function getHeaders()
    {
        return [
        ];
        
    }
    public function getIPlocation()
    {
        return $this->getData(self::VERSION.'/'.self::IP_GEOLOCATION_API,[
            'apiKey'=>$this->apiKey,
        ]);
    }
}
