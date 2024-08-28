<?php
namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface BrandRepositoryInterface extends RepositoryInterface
{
    public function getHotBrands();
}
