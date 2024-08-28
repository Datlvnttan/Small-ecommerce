<?php

namespace Modules\Elastic\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;


class SpecificationNameGroupsAggregationsElasticSearchFilterTransformer extends TransformerAbstract
{
    protected $specificationValuesSelected;
    public function __construct($specificationValuesSelected = null)
    {
        $this->specificationValuesSelected = $specificationValuesSelected;
    }
    public function transform($bucket)
    {
        return [
            'specification_name' => ucfirst($bucket['key']),
            'count' => $bucket['doc_count'],
            'specificationValues'=>$this->includeSpecificationValues($bucket)
        ];
    }
    private function includeSpecificationValues($bucket)
    {
        $fractal = new Manager();
        $specificationValues = $bucket['specification_values']['buckets'];
        $result = $this->collection($specificationValues, new SpecificationValueAggregationsElasticSearchFilterTransformer($this->specificationValuesSelected));
        $data = $fractal->createData($result)->toArray();
        return $data['data'];
    }
}
