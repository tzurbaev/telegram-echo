<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMemberToChannel;
use App\Http\Requests\RemoveMemberFromChannel;
use App\Exceptions\Api\UserWasNotFoundException;

class ChannelMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Channel             $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Channel $channel)
    {
        return response()->json([
            'success' => 1,
            'data' => $channel->members,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AddMemberToChannel $request
     * @param \App\Channel                          $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddMemberToChannel $request, Channel $channel)
    {
        $user = User::find($request->input('user_id'));

        if (is_null($user)) {
            throw new UserWasNotFoundException();
        }

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
     * @param \App\Channel                               $channel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RemoveMemberFromChannel $request, Channel $channel)
    {
        $user = User::find($request->input('user_id'));

        if (is_null($user)) {
            throw new UserWasNotFoundException();
        }

        $channel->removeMember($user);

        return response()->json([
            'success' => 1,
            'data' => $channel->members,
        ]);
    }
}
