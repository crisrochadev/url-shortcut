<?php

namespace App\Http;

use Illuminate\Http\Response as HttpResponse;

class Response
{
    static function success($data)
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_OK
        );
    }

    static function notFound($data)
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_NOT_FOUND
        );
    }
    static function created($data)
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_ACCEPTED
        );
    }
    static function error($data)
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_BAD_REQUEST
        );
    }
}
