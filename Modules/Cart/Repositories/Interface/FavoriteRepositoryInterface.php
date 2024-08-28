<?php
namespace Modules\Cart\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface FavoriteRepositoryInterface extends RepositoryInterface
{
    public function getProductFavoriteByUserId($userId, $member_type = "guest");
}
