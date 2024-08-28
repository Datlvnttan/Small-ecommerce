<?php
namespace Modules\Seller\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface SellerRepositoryInterface extends RepositoryInterface
{
    public function getAllWithRelationship($perPage = null);
}
