<?php

namespace Modules\Order\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Services\FeedbackOrderService;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Sku;

class OrderDetail extends ModelCompoundPrimaryKey
{
    use HasFactory;

    public $table = 'order_details';

    protected $primaryKey = ['order_id', 'sku_id'];
    protected $fillable = [
        'order_id',
        'sku_id',
        'price',
        'quantity',
        'options',
        'feedback_rating',
        'feedback_image',
        'feedback_review',
        'feedback_status',
        'feedback_created_at',
        'feedback_is_updated',
        'feedback_incognito'
    ];
    protected $casts = [
        'order_id' => 'integer',
        'sku_id' => 'integer',
        'price' => 'integer',
        'quantity' => 'integer',
        'feedback_image' => 'string',
        'feedback_review' => 'string',
        'feedback_created_at' => 'datetime',
        'options' => 'string',
        'feedback_rating' => 'integer',
        'feedback_status' => 'boolean',
        'feedback_is_updated' => 'boolean',
        'feedback_incognito' => 'boolean'
    ];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderDetailFactory::new();
    }
    public function sku()
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }
    // public function getProductAttribute()
    // {
    //     return $this->sku->product;
    // }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getFeedbackPathImageAttribute()
    {
        if (isset($this->feedback_image)) {
            // $imageName = $this->getAttribute('feedback_image');
            return url(FeedbackOrderService::FEEDBACK_IMAGE_PATH . '/' . $this->feedback_image);
        }
        return null;
    }
    protected $appends = ['feedback_path_image'];

    // protected static function booted()
    // {
    //     static::addGlobalScope('sku', function (Builder $builder) {
    //         $builder->without('sku');
    //     });
    // }
}
