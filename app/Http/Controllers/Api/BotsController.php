<?php

namespace App\Http\Controllers\Api;

use App\Bot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\BotTransformer;
use App\Http\Requests\StoreBotRequest;
use App\Http\Requests\UpdateBotRequest;

class BotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bots = $request->user()->bots()->get();

        return response()->json([
            'success' => 1,
            'data' => $bots,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreBotRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBotRequest $request)
    {
        $bot = $request->user()->bots()->create([
            'external_id' => 0,
            'name' => '',
            'username' => '',
            'token' => $request->input('token'),
        ]);

        return response()->json([
            'success' => 1,
            'data' => fractal($bot, new BotTransformer())->toArray(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Bot $bot
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Bot $bot)
    {
        return response()->json([
            'success' => 1,
            'data' => $bot,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateBotRequest $request
     * @param \App\Bot                            $bot
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBotRequest $request, Bot $bot)
    {
        $fields = $this->withoutNulls($request, ['token']);
        $bot->update($fields);

        return response()->json([
            'success' => 1,
            'data' => $bot,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Bot $bot
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bot $bot)
    {
        $bot->delete();

        return response()->json([
            'success' => 1,
        ]);
    }
}
