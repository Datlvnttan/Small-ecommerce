<?php

namespace Modules\Product\Transformers;

use App\Helpers\Helper;
use League\Fractal\TransformerAbstract;


class FeedbackTransformer extends TransformerAbstract
{
    public function transform($feedback)
    {
        $nickname = null;
        if(isset($feedback->feedback_incognito) && $feedback->feedback_incognito == true)
        {
            $nickname = Helper::subMasked($feedback->nickname,3);
        }
        else
        {
            $nickname = $feedback->nickname;
        }
        return [
            'product_id'=>$feedback->product_id,
            'sku_id'=>$feedback->sku_id,
            'feedback_title'=>$feedback->feedback_title,
            'feedback_rating'=>$feedback->feedback_rating,
            'feedback_review'=>$feedback->feedback_review,
            'feedback_created_at'=>$feedback->feedback_created_at,
            'options'=>$feedback->options,
            'feedback_path_image'=>$feedback->feedback_path_image,
            'nickname'=>$nickname,
        ];
    }
}
