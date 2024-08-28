<?php

namespace Modules\Seller\Services;

use Modules\Elastic\Services\SellerElasticService;
use Modules\Seller\Repositories\Interface\SellerRepositoryInterface;

class SellerService
{
    public const PER_PAGE = 10;
    protected $sellerRepositoryInterface;
    protected $sellerElasticServer;
    public function __construct(SellerElasticService $sellerElasticService, SellerRepositoryInterface $sellerRepositoryInterface)
    {
        $this->sellerElasticServer = $sellerElasticService;
        $this->sellerRepositoryInterface = $sellerRepositoryInterface;
    }

    public function all($isPaginate = true)
    {
        $perPage = null;
        if ($isPaginate) {
            $perPage = self::PER_PAGE;
        }
        return $this->sellerRepositoryInterface->all(perPage: $perPage);
    }
    public function getAllWithRelationship($isPaginate = true)
    {
        $perPage = null;
        if ($isPaginate) {
            $perPage = self::PER_PAGE;
        }
        return $this->sellerRepositoryInterface->getAllWithRelationship(perPage: $perPage);
    }
    public function getAllWithRelationship2()
    {
        return $this->sellerElasticServer->getSyncDataFromDB();
    }
}
