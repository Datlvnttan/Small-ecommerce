<?php
namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface ProductAttributeOptionRepositoryInterface extends RepositoryInterface
{
    public function getProductAttributeOptionByProductId($productId);
}
