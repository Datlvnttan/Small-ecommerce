<?php

namespace Modules\Content\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Content\Services\PostService;

class PostApiController extends Controller
{
    protected $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $tag = $request->tag;
            if (isset($tag))
                $posts = $this->postService->getNewPosts();
            else
                $posts = $this->postService->all();
            return ResponseJson::success(data: $posts);
        });
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
