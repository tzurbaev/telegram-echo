<?php

namespace App\Http\Controllers\Api;

use App\Channel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Jobs\PublishScheduledPost;
use App\Http\Controllers\Controller;
use App\Exceptions\Api\BotWasNotFoundException;
use App\Exceptions\Api\PostWasNotFoundException;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $request->user()->posts()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => 1,
            'data' => $posts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePost $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $channel = Channel::find($request->input('channel_id'));

        if (!$channel->hasBot()) {
            throw new BotWasNotFoundException();
        }

        $scheduledAt = $this->extractScheduledPostDate($request);

        $post = $request->user()->posts()->create([
            'message' => $request->input('message'),
            'scheduled_at' => $scheduledAt,
            'published_at' => null,
        ]);

        if ($request->has('attachments')) {
            $post->setAttachments($request->input('attachments'));
        }

        $post->shouldBePublishedWith($channel->bot, $channel);

        $publicationDispatched = false;

        if ($post->canBePublishedNow()) {
            dispatch(new PublishScheduledPost($post));

            $publicationDispatched = true;
        }

        return response()->json([
            'success' => 1,
            'data' => $post,
            'publication_dispatched' => $publicationDispatched ? 1 : 0,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $post = $request->user()->posts()->find($id);

        if (is_null($post)) {
            throw new PostWasNotFoundException();
        }

        return response()->json([
            'success' => 1,
            'data' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePost $request, $id)
    {
        $post = $request->user()->posts()->find($id);

        $scheduledAt = $this->extractScheduledPostDate($request);
        $fields = $this->withoutNulls($request, ['channel_id', 'message']);

        $post->update(array_merge($fields, [
            'scheduled_at' => $scheduledAt,
        ]));

        $removeAttachments = $request->has('remove_attachments');

        if ($removeAttachments) {
            $post->removeAttachments();
        } elseif ($request->has('attachments')) {
            $post->setAttachments($request->input('attachments'));
        }

        $publicationDispatched = false;

        if ($post->canBePublishedNow() && !$post->wasPublished()) {
            dispatch(new PublishScheduledPost($post));

            $publicationDispatched = true;
        }

        return response()->json([
            'success' => 1,
            'data' => $post,
            'publication_dispatched' => $publicationDispatched,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = $request->user()->posts()->find($id);

        if (is_null($post)) {
            throw new PostWasNotFoundException();
        }

        $post->delete();

        return response()->json([
            'success' => 1,
        ]);
    }

    /**
     * Возвращает дату публикации отложенной записи (если передана).
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Carbon\Carbon|null
     */
    protected function extractScheduledPostDate(Request $request)
    {
        $scheduledAt = null;
        $timezone = $request->user()->timezone;

        if ($request->has('scheduled_at')) {
            $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $request->input('scheduled_at'), $timezone)
                ->setTimezone('UTC');
        }

        return $scheduledAt;
    }
}
