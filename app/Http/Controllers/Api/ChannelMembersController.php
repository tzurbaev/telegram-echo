<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Exceptions\Api\UserWasNotFoundException;
use App\Http\Requests\AddMemberToChannelRequest;
use App\Http\Requests\RemoveMemberFromChannelRequest;

class ChannelMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Channel $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Channel $channel)
    {
        return response()->json([
            'success' => 1,
            'data' => $channel->getMembers(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AddMemberToChannelRequest $request
     * @param \App\Channel                                 $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddMemberToChannelRequest $request, Channel $channel)
    {
        $user = User::find($request->input('user_id'));

        if (is_null($user)) {
            throw new UserWasNotFoundException();
        }

        $channel->addMember($user);

        return response()->json([
            'success' => 1,
            'data' => $channel->getMembers(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\RemoveMemberFromChannelRequest $request
     * @param \App\Channel                                      $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RemoveMemberFromChannelRequest $request, Channel $channel)
    {
        $user = User::find($request->input('user_id'));

        if (is_null($user)) {
            throw new UserWasNotFoundException();
        }

        $channel->removeMember($user);

        return response()->json([
            'success' => 1,
            'data' => $channel->getMembers(),
        ]);
    }
}
