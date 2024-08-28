<?php

namespace Modules\External\Services;

class WebikeExternalApiService extends BaseExternalApiService
{
    public function __construct()
    {
        parent::__construct();
    }
    protected function getBaseUrl()
    {
        return 'https://shop.webike.vn';
    }
    protected function getHeaders()
    {
        return [
        ];
        
    }
}
