<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMemberToChannel;
use App\Http\Requests\RemoveMemberFromChannel;
use App\Exceptions\Api\UserWasNotFoundException;
use App\Exceptions\Api\ChannelWasNotFoundException;

class ChannelMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $id)
    {
        $channel = $request->user()->channels()->find($id);

        if (is_null($channel)) {
            throw new ChannelWasNotFoundException();
        }

        return response()->json([
            'success' => 1,
            'data' => $channel->members,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AddMemberToChannel $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddMemberToChannel $request, $id)
    {
        $user = User::find($request->input('user_id'));

        if (is_null($user)) {
            throw new UserWasNotFoundException();
        }

        $channel = $request->user()->channels()->find($id);

        $channel->addMember($user);

        return response()->json([
            'success' => 1,
            'data' => $channel->members,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\RemoveMemberFromChannel $request
     * @param int                                        $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RemoveMemberFromChannel $request, $id)
    {
        $user = User::find($request->input('user_id'));

        if (is_null($user)) {
            throw new UserWasNotFoundException();
        }

        $channel = $request->user()->channels()->find($id);

        $channel->removeMember($user);

        return response()->json([
            'success' => 1,
            'data' => $channel->members,
        ]);
    }
}
