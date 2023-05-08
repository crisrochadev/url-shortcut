<?php

namespace App\Http;

use Illuminate\Http\Response as HttpResponse;

class Response
{
    /**
     * Método responsável por enviar resposta de sucesso
     *
     * @param [type] $data
     * @return \Illuminate\Http\JsonResponse
     */
    static function success($data): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_OK
        );
    }

    /**
     * Método responsável por enviar resposta de não encontrado
     *
     * @param [type] $data
     * @return \Illuminate\Http\JsonResponse
     */
    static function notFound($data): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_NOT_FOUND
        );
    }

    /**
     * Método responsável por enviar resposta de  dados criados
     *
     * @param [type] $data
     * @return \Illuminate\Http\JsonResponse
     */
    static function created($data): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_ACCEPTED
        );
    }

    /**
     * Método responsável por enviar resposta de erro
     *
     * @param [type] $data
     * @return \Illuminate\Http\JsonResponse
     */
    static function error($data): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $data,
            HttpResponse::HTTP_BAD_REQUEST
        );
    }
}
