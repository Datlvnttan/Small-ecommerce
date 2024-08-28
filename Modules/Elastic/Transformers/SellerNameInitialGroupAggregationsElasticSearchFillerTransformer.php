<?php

namespace Modules\Elastic\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;


class SellerNameInitialGroupAggregationsElasticSearchFillerTransformer extends TransformerAbstract
{
    protected $sellerIdsSelected;
    public function __construct($sellerIdsSelected = null)
    {
        $this->sellerIdsSelected = $sellerIdsSelected;
    }
    public function transform($bucket)
    {
        return [
            'key' => $bucket['key'],
            'count' => $bucket['doc_count'],
            'sellers'=>$this->includeSellerDetails($bucket)
        ];
    }
    private function includeSellerDetails($bucket)
    {
        $fractal = new Manager();
        $sellers = $bucket['sellers']['buckets'];
        $result = $this->collection($sellers, new SellerDetailsBySellerNameInitialGroupAggregationsElasticSearchFillerTransformer($this->sellerIdsSelected));
        $data = $fractal->createData($result)->toArray();
        return $data['data'];
    }
}
