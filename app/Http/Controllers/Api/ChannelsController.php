<?php

namespace App\Http\Controllers\Api;

use App\Channel;
use Illuminate\Http\Request;
use App\Http\Requests\StoreChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateChannel;
use App\Transformers\ChannelTransformer;
use App\Contracts\Channels\ChannelsFactoryContract;

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
     * @param \Illuminate\Http\Request
     * @param \App\Channel $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Channel $channel)
    {
        return response()->json([
            'success' => 1,
            'data' => fractal($channel, new ChannelTransformer())->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateChannel $request
     * @param \App\Channel                     $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateChannel $request, Channel $channel)
    {
        $fields = $this->withoutNulls($request, ['name', 'chat_id', 'bot_id']);
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
     * @param \App\Channel $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Channel $channel)
    {
        $channel->delete();

        return [
            'success' => 1,
        ];
    }
}
