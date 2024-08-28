<?php

namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface ProductImageRepositoryInterface extends RepositoryInterface
{
    public function allByProductId($productId);
}
