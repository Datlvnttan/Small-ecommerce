<?php
namespace Modules\Content\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function getNewPosts();
}
