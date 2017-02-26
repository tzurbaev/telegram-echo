<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreBot;
use App\Http\Requests\UpdateBot;
use App\Http\Controllers\Controller;
use App\Transformers\BotTransformer;
use App\Exceptions\Http\EmptyRequestException;
use App\Exceptions\Api\BotWasNotFoundException;

class BotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
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
     * @param  \App\Http\Requests\StoreBot $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBot $request)
    {
        $bot = $request->user()->bots()->create($request->only(['name', 'username', 'token']));

        return response()->json([
            'success' => 1,
            'data' => fractal($bot, new BotTransformer())->toArray(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $bot = $request->user()->bots()->find($id);

        if (is_null($bot)) {
            throw new BotWasNotFoundException();
        }

        return response()->json([
            'success' => 1,
            'data' => $bot,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBot $request
     * @param  int                          $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBot $request, $id)
    {
        $bot = $request->user()->bots()->find($id);

        if (is_null($bot)) {
            throw new BotWasNotFoundException();
        }

        $fields = $this->withoutNulls($request, ['name', 'username', 'token']);

        if (!count($fields)) {
            throw new EmptyRequestException();
        }

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
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $bot = $request->user()->bots()->find($id);

        if (is_null($bot)) {
            throw new BotWasNotFoundException();
        }

        $bot->delete();

        return response()->json([
            'success' => 1,
        ]);
    }
}
