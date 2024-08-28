<?php

namespace Modules\Content\Services;

use Modules\Content\Repositories\Interface\PostRepositoryInterface;

class PostService
{
    protected $postRepositoryInterface;
    public function __construct(PostRepositoryInterface $postRepositoryInterface)
    {
        $this->postRepositoryInterface = $postRepositoryInterface;
    }

    public function getNewPosts()
    {
        return $this->postRepositoryInterface->getNewPosts();
    }
    public function all()
    {
        return $this->postRepositoryInterface->all();
    }
}
