<?php

namespace App\Http\Controllers\Api;

use App\Post;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use App\Http\Requests\StorePostRequest;
use App\Jobs\PublishScheduledPost;
use App\Http\Controllers\Controller;
use App\Transformers\PostTransformer;
use App\Http\Requests\UpdatePostRequest;
use App\Contracts\Posts\PostsFactoryContract;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $posts = $request->user()->posts()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => 1,
            'data' => fractal($posts, new PostTransformer())->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePostRequest       $request
     * @param \App\Contracts\Posts\PostsFactoryContract $posts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request, PostsFactoryContract $posts, DateTimeHelper $dates)
    {
        $post = $request->createPost($posts, $dates);

        $request->user()->posts()->save($post);

        $jobDispatched = false;

        if ($post->canBePublishedNow()) {
            dispatch(new PublishScheduledPost($post));

            $jobDispatched = true;
        }

        return response()->json([
            'success' => 1,
            'data' => fractal($post, new PostTransformer())->toArray(),
            'publication_dispatched' => $jobDispatched ? 1 : 0,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Post $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        return response()->json([
            'success' => 1,
            'data' => fractal($post, new PostTransformer())->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePostRequest $request
     * @param \App\Helpers\DateTimeHelper          $dates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, DateTimeHelper $dates)
    {
        $fields = $this->withoutNulls($request, ['channel_id', 'title', 'message']);
        $post = $request->updatePost($fields, $dates);

        $publicationDispatched = false;

        if ($post->canBePublishedNow() && !$post->wasPublished()) {
            dispatch(new PublishScheduledPost($post));

            $publicationDispatched = true;
        }

        return response()->json([
            'success' => 1,
            'data' => fractal($post, new PostTransformer())->toArray(),
            'publication_dispatched' => $publicationDispatched,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Post $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'success' => 1,
        ]);
    }
}
