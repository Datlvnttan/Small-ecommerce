<?php

namespace Modules\External\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BaseExternalApiService
{
    protected $apiKey;
    public $baseUrl;
    protected $headers;
    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
        $this->headers = $this->getHeaders();
        
    }
    abstract protected function getBaseUrl();
    abstract protected function getHeaders();
    public function getData(string $endpoint, $params = [])
    {
        // dd($params);
        // Log::info($endpoint);
        $response = Http::withHeaders($this->headers)->get("{$this->baseUrl}/{$endpoint}", $params);
        // dd($response);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Error fetching data from external API');
    }

    public function postData($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers)->post("{$this->baseUrl}/{$endpoint}", $data);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Error posting data to external API');
    }
    public function putData($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers)->put("{$this->baseUrl}/{$endpoint}", $data);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Error putting data to external API');
    }
    public function patchData($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers)->patch("{$this->baseUrl}/{$endpoint}", $data);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Error putting data to external API');
    }
    public function deleteData($endpoint, $data = [])
    {
        $response = Http::withHeaders($this->headers)->delete("{$this->baseUrl}/{$endpoint}", $data);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Error delete data to external API');
    }
}
