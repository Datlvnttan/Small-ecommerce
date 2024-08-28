<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\Interface\BrandRepositoryInterface;

class BrandService
{
    protected $brandRepository;
    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function all()
    {
        return $this->brandRepository->all(perPage:config('config.perPage'));
    }
    public function getHotBrands()
    {
        return $this->brandRepository->getHotBrands();
    }
}
