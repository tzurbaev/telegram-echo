<?php

namespace App\Http\Controllers\Api;

use App\Bot;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBot;
use App\Http\Requests\UpdateBot;
use App\Http\Controllers\Controller;
use App\Transformers\BotTransformer;

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
     * @param \App\Http\Requests\StoreBot $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBot $request)
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
     * @param \Illuminate\Http\Request
     * @param \App\Bot $bot
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Bot $bot)
    {
        return response()->json([
            'success' => 1,
            'data' => $bot,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateBot $request
     * @param \App\Bot                     $bot
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBot $request, Bot $bot)
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
     * @param \Illuminate\Http\Request
     * @param \App\Bot $bot
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Bot $bot)
    {
        $bot->delete();

        return response()->json([
            'success' => 1,
        ]);
    }
}
