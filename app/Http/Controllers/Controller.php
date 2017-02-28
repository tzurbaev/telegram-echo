<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exceptions\Http\EmptyRequestException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Возвращает поля запроса без NULL-значений.
     *
     * @param \Illuminate\Http\Request $request
     * @param array                    $fields
     *
     * @return array
     */
    protected function withoutNulls(Request $request, array $fields, bool $throwExceptionIfEmpty = true): array
    {
        $data = $request->only($fields);

        $fields = collect($data)->reject(null)->toArray();

        if (!count($fields) && $throwExceptionIfEmpty === true) {
            throw new EmptyRequestException();
        }

        return $fields;
    }
}
