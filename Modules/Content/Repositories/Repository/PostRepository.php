<?php

namespace Modules\Content\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Modules\Content\Repositories\Interface\PostRepositoryInterface;

class PostRepository extends EloquentRepository implements PostRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Content\Entities\Post::class;
    }
    public function getNewPosts()
    {
        return $this->model
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    }
}
