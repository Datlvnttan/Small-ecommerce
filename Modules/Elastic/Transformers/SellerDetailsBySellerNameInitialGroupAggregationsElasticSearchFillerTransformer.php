<?php

namespace Modules\Elastic\Transformers;

use League\Fractal\TransformerAbstract;


class SellerDetailsBySellerNameInitialGroupAggregationsElasticSearchFillerTransformer extends TransformerAbstract
{
    protected $sellerIdsSelected;
    public function __construct($sellerIdsSelected = null)
    {
        $this->sellerIdsSelected = $sellerIdsSelected;
    }
    public function transform($seller)
    {
        $id = $seller['sellerDetail']['hits']['hits'][0]['_source']['id'];
        $selected = false;
        if(isset($this->sellerIdsSelected))
        {
            $selected = in_array($id, $this->sellerIdsSelected);
        }
        return [
            'id' => $id,
            'logo' => $seller['sellerDetail']['hits']['hits'][0]['_source']['logo'],
            'seller_name' => ucfirst($seller['key']),
            'seller_name_format' => $this->formatSellerName($seller),
            'selected' => $selected,
            'count' => $seller['doc_count'],
        ];
    }
    private function formatSellerName($bucket)
    {
        $key = ucfirst($bucket['key']);
        if (mb_strlen($key) > 25) {
            $key = mb_substr($key, 0, 25) . "...";
        }
        return $key;
    }
}
