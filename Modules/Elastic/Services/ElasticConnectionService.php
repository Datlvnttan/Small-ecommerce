<?php

namespace Modules\Elastic\Services;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use KitLoong\MigrationsGenerator\Schema\Models\Index;

class ElasticConnectionService
{
    private static Client $elasticClient;
    private static $instance = null;
    // Đọc cấu hình từ file
    // protected $config = require 'config/elasticsearch.php';
    public function __construct()
    {
        $host = config('elasticsearch.host');

        $clientBuilder = ClientBuilder::create()
            ->setHosts([$host]);
        $isAuthen = config('elasticsearch.isAuthen');
        if ($isAuthen) {
            $userName = config('elasticsearch.username');
            $password = config('elasticsearch.password');
            if (isset($userName) && isset($password)) {
                $clientBuilder->setBasicAuthentication($userName, $password);
            }
        }
        self::$elasticClient = $clientBuilder->build();
    }
    public function getElasticClient()
    {
        return self::$elasticClient;
    }
    // Ngăn chặn việc sao chép đối tượng Singleton
    public function __clone()
    {
    }

    // Ngăn chặn việc deserialization đối tượng Singleton
    public function __wakeup()
    {
    }
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function checkConnection()
    {
        self::$elasticClient->ping();
        return true;
    }
}
