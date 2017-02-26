<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateChannel;
use App\Transformers\ChannelTransformer;
use App\Exceptions\Http\EmptyRequestException;
use App\Contracts\Channels\ChannelsFactoryContract;
use App\Exceptions\Api\ChannelWasNotFoundException;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $channels = $request->user()->channels()->get();

        return response()->json([
            'success' => 1,
            'data' => fractal($channels, new ChannelTransformer())->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreChannel $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreChannel $request, ChannelsFactoryContract $channels)
    {
        $user = $request->user();
        $bot = $user->bots()->find($request->input('bot_id'));

        $channel = $channels->make($user, $request->input('name'), $request->input('chat_id'));
        $channel->bot()->associate($bot);
        $channel->save();

        return response()->json([
            'success' => 1,
            'data' => fractal($channel, new ChannelTransformer())->toArray(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $channel = $request->user()->channels()->find($id);

        if (is_null($channel)) {
            throw new ChannelWasNotFoundException();
        }

        return response()->json([
            'success' => 1,
            'data' => fractal($channel, new ChannelTransformer())->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateChannel $request
     * @param int                              $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateChannel $request, $id)
    {
        $channel = $request->user()->channels()->find($id);

        if (is_null($channel)) {
            throw new ChannelWasNotFoundException();
        }

        $fields = $this->withoutNulls($request, ['name', 'chat_id', 'bot_id']);

        if (!count($fields)) {
            throw new EmptyRequestException();
        }

        $channel->update($fields);

        return response()->json([
            'success' => 1,
            'data' => fractal($channel, new ChannelTransformer())->toArray(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $channel = $request->user()->channels()->find($id);

        if (is_null($channel)) {
            throw new ChannelWasNotFoundException();
        }

        $channel->delete();

        return [
            'success' => 1,
        ];
    }
}
