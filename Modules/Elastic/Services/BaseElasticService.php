<?php

namespace Modules\Elastic\Services;

use Carbon\Carbon;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use KitLoong\MigrationsGenerator\Schema\Models\Index;


abstract class BaseElasticService
{
    protected $model;
    protected string $index;
    protected array $properties;
    protected $key = 'id';
    protected $fieldSuggest = null;
    protected $keySuggest = 'suggest';
    protected $sourcesSuggest = false;
    protected $elasticConnection = null;
    protected $pipelineId = null;
    
    public function __construct()
    {
        $this->elasticConnection = ElasticConnectionService::instance();
        $this->setIndexMappings();
    }
    abstract public function getProperties();
    abstract protected function getModel(): string;
    protected function getSettings()
    {
        return [];
    }
    protected function getPipelineBody(): array|null
    {
        return null;
    }
    protected function buildPipeline()
    {
        if (isset($this->pipelineId)) {
            $pipelines = $this->getPipelineBody();
            if (isset($pipelines)) {
                $params = [
                    'id' => $this->pipelineId,
                    'body' => $pipelines
                ];
                $this->elasticConnection->getElasticClient()->ingest()->putPipeline($params);
            }
        }
    }
    public function getIndex()
    {
        return $this->index;
    }
    public function getKeySuggest()
    {
        return $this->keySuggest;
    }public function getSourcesSuggest()
    {
        return $this->sourcesSuggest;
    }
    protected function setIndexMappings()
    {
        $modelName = $this->getModel();
        if (class_exists($modelName)) {
            $this->model = app()->make($modelName);
            $this->index = $this->model->getTable();
            $this->properties = $this->getProperties();
        } else {
            throw new \Exception('Model does not exist');
        }
    }
    public function buildIndexMapping()
    {
        $params = [
            'index' => $this->index,
        ];
        $body = [];
        if (count($this->getSettings()) > 0) {
            $body['settings'] =  $this->getSettings();
        }
        $body['mappings'] =  [
            'properties' =>  $this->properties,
        ];
        $params['body'] = $body;
        $this->elasticClient()->indices()->create($params);
    }
    public function exists()
    {
        $response = $this->elasticClient()->indices()->exists(['index' => $this->index]);
        return $response->getStatusCode() != 404;
    }
    protected function getSyncDataFromDB($page = null)
    {
        $skip = 100 * ($page - 1);

        return $this->model->skip($skip)->limit(100)->get();
    }
    public function elasticClient()
    {
        return $this->elasticConnection->getElasticClient();
    }
    /**
     * Đồng bộ db lên Elastic
     * @return bool
     */
    public function syncDatabaseToElasticsearch()
    {
        ini_set('max_execution_time', 900);
        if ($this->exists()) {
            $this->elasticClient()->indices()->delete(['index' => $this->index]);
            // return true;
        }
        if (!$this->exists()) {
            $this->buildIndexMapping();
            $this->buildPipeline();
        }
        // return 1;
        $data = null;
        $page = 1;
        do {
            $data = $this->getSyncDataFromDB($page);
            // return $data;  
            if (isset($data['data']))
                $data = $data['data'];
            if (count($data) == 0)
                return true;
            $actions = [];
            foreach ($data as $item) {
                $index = [
                    '_index' => $this->index,
                ];
                if (isset($this->pipelineId)) {
                    $index['pipeline'] = $this->pipelineId;
                }
                if (!is_bool($this->key)) {
                    if (!is_array($this->key)) {
                        $index['_id'] = $item->{$this->key};
                    } else {
                        foreach ($this->key as $k) {
                            $index['_id'][$k] = $item->{$k};
                        }
                    }
                }
                $actions[] = [
                    'index' => $index
                ];
                $actions[] = $item;
                // $suggest = $this->properties[$this->keySuggest];
                // if (isset($suggest)) {
                //     if ($suggest['type'] == 'completion' && isset($this->fieldSuggest)) {
                //         $value = $item->getAttribute($this->fieldSuggest);
                //         if (isset($value)) {
                //             $document[$this->keySuggest] = [
                //                 'input' => [$value],
                //             ];
                //         }
                //     }
                // }
                // $actions[] = $this->buildDataFollowProperties($this->properties, $item);
            }
            $this->elasticClient()->bulk([
                'body' => $actions,
            ]);
            // return true;
            Log::info('Xong ' . $page);
            $page += 1;
        } while (true);
        // return $actions;
        // if (count($actions) > 0) {
        //     $this->elasticClient()->bulk([
        //         'body' => $actions,
        //     ]);
        // }
        // return true;
    }
    protected function buildDataFollowProperties($properties, $item)
    {
        // try {
        $document = [];
        if (isset($item)) {
            if (is_array($item)) {
                $item = collect($item);
            }
            foreach ($properties as $key => $value) {
                $document[] = $key;
                $document[] = $item->{$key};
                if (isset($item->{$key}) && $value['type'] == 'nested' || $value['type'] == 'object') {
                    $subProperties = $value['properties'];
                    $document[] = $this->buildDataFollowProperties($subProperties, $item->{$key});
                }

                continue;
                // if ($key == 'product_flash_sale_active')

                // else {
                //     continue;
                // }
                if (!isset($value['type'])) {
                    $document[$key] = $item->{$key};
                } elseif ($value['type'] == 'nested') {
                    $subProperties = $value['properties'];
                    $document[$key] = $item->{$key}->map(function ($subItem) use ($subProperties) {
                        return $this->buildDataFollowProperties($subProperties, $subItem);
                    });
                } elseif ($value['type'] == 'object') {
                    $subProperties = $value['properties'];
                    $document[$key] = $this->buildDataFollowProperties($subProperties, $item->{$key});
                } elseif ($value['type'] == 'date') {
                    $document[$key] = Carbon::parse($item->{$key});
                } else {
                    $document[$key] = $item->{$key};
                }
            }
            return $document;
        }

        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }
    public function suggest()
    {
        // return $this->model->all();
        $params = [
            'index' => $this->index,
            'body'  => [
                'query' => [
                    'match_all' => (object)[]
                ]
            ],
        ];
        return $this->elasticClient()->search($params)['hits']['hits'];
    }
    public function all()
    {
        // return $this->model->all();
        $params = [
            'index' => $this->index,
            'body'  => [
                'query' => [
                    'match_all' => (object)[]
                ]
            ],
        ];
        return $this->elasticClient()->search($params)['hits']['hits'];
    }
}
