<?php

namespace App\Http\Controllers;

use App\Services\ShortenerServices;
use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests\ShortenerRequest;

class ShortenerController extends Controller
{

    /**
     * @var ShortenerServices
     */
    protected $shortenerServices;
    public function __construct(ShortenerServices $shortenerServices)
    {
        $this->shortenerServices = $shortenerServices;
    }

    /**
     * Método responsável por enviar o link através do seu slug
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(string $slug): \Illuminate\Http\JsonResponse
    {
        $response = $this->shortenerServices->getBySlug($slug);
        if (array_key_exists('error',$response)) {
            return Response::notFound($response);
        }
        return Response::success($response);
    }


    /**
     * Método responsável por criar um novo link
     *
     * @param ShortenerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShortenerRequest $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->shortenerServices->createShortcut($request->url);
        if (array_key_exists('error',$response)) {
            return Response::notFound($response);
        }
        return Response::created($response);
    }


    /**
     * Método responsável por retornar os links conforme paginação
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->shortenerServices->get();
        if (array_key_exists('error',$response)) {
            return Response::notFound($response);
        }
        return Response::success($response);
    }
     /**
     * Método responsável por retornar os links desabilitados conforme paginação
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showDisabled(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->shortenerServices->getDisabled();
        if (array_key_exists('error',$response)) {
            return Response::notFound($response);
        }
        return Response::success($response);
    }

    /**
     * Método responsável por reativar um link expirado
     *
     * @param [type] $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reactivate($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->shortenerServices->reactivate($id);
        if (array_key_exists('error',$response)) {
            return Response::notFound($response);
        }
        return Response::success($response);
    }

    /**
     * Método responsável por desabilitar um link
     *
     * @param [type] $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->shortenerServices->disable($id);
        if (array_key_exists('error',$response)) {
            return Response::notFound($response);
        }
        return Response::success($response);
    }
}
