<?php

namespace Modules\Elastic\Transformers;

use League\Fractal\TransformerAbstract;


class SuggestTransformer extends TransformerAbstract
{
    // private $filedSource = null;
    // public function __construct($filedSource = null)
    // {
    //     $this->filedSource = $filedSource;
    // }
    public function transform($suggest)
    {
        $data = [
            '_index' => $suggest->_index,
            '_id' => $suggest->_id
        ];
        if (isset($suggest->_source)) {
            foreach ($suggest->_source as $key => $value) {
                $data['text'] = $value;
            }
            // $data['text'] = $suggest['_source'][$this->filedSource];
        } else {
            $data['text'] = $suggest->text;
        }
        return $data;
    }
}
