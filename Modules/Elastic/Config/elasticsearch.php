<?php

return [
    'host' => env('ELASTICSEARCH_HOST') . ':' . env('ELASTICSEARCH_PORT'),
    'apiKey' => env('ELASTICSEARCH_API_KEY'),
    'isAuthen' => env('ELASTICSEARCH_IS_AUTHENTICATION'),
    'username' => env('ELASTICSEARCH_USERNAME'),
    'password' => env('ELASTICSEARCH_PASSWORD'),
];
