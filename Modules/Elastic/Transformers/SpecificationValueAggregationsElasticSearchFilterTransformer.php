<?php

namespace Modules\Elastic\Transformers;

use League\Fractal\TransformerAbstract;


class SpecificationValueAggregationsElasticSearchFilterTransformer extends TransformerAbstract
{
    protected $specificationValuesSelected;
    public function __construct($specificationValuesSelected = null)
    {
        $this->specificationValuesSelected = $specificationValuesSelected;
    }
    public function transform($bucket)
    {
        $selected = false;
        if (isset($this->specificationValuesSelected)) {
            $selected = in_array($bucket['key'], $this->specificationValuesSelected);
        }
        return [
            'specification_value' => ucfirst($bucket['key']),
            'specification_value_format' => $this->formatSpecificationValue($bucket),
            'count' => $bucket['doc_count'],
            'selected' => $selected
        ];
    }
    private function formatSpecificationValue($bucket)
    {
        $key = ucfirst($bucket['key']);
        if (mb_strlen($key) > 25) {
            $key = mb_substr($key, 0, 25) . "...";
        }
        return $key;
    }
}
