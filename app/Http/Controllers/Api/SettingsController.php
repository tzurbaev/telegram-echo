<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateSettingsRequest;

class SettingsController extends Controller
{
    /**
     * @param \App\Http\Requests\UpdateSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSettingsRequest $request)
    {
        $common = $this->withoutNulls($request, ['name', 'email'], false);
        $user = $request->user();

        if (count($common) > 0) {
            $user->fill($common);
        }

        if (!$request->has('password')) {
            $user->save();

            return response()->json([
                'success' => 1,
                'password_changed' => 1,
            ]);
        }

        $passwordMatches = Hash::check($request->input('current_password'), $user->password);

        if ($passwordMatches === false) {
            return response()->json([
                'error' => 'Password mismatch.',
                'code' => 'password_mismatch',
            ], 403);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'success' => 1,
            'password_changed' => 1,
        ]);
    }
}
