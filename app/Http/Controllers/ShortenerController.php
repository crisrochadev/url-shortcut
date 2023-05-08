<?php

namespace App\Http\Controllers;




use App\Models\Shortener;
use App\Services\ShortenerServices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Log;
use App\Http\Response;
use App\Http\Requests\ShortenerRequest;

class ShortenerController extends Controller
{

    protected $shortenerServices;
    public function __construct(ShortenerServices $shortenerServices){
        $this->shortenerServices = $shortenerServices;
    }
    /**
     * Metodo responsável por redirecionar para uma URL com base em um determinado slug, caso exista e não tenha expirado ou
     * desativado, caso contrário, redirecionar para uma página 404.
     *
     * @param  $slug O parâmetro slug é uma variável de string que representa o identificador único para
     * uma URL abreviada. Ele é usado para consultar o banco de dados e recuperar o URL completo correspondente para
     * redirecionamento.
     *
     *  Um link curto válido com o slug fornecido for encontrado e não tiver expirado ou
     * desativado, a função redirecionará o usuário para a URL correspondente. Se o link não for encontrado
     * ou expirou ou foi desativado, a função redirecionará o usuário para uma página de erro 404.
     */

    public function index($slug)
    {
        $response = $this->shortenerServices->getBySlug($slug);
        if(!$response['success']){
            return Response::notFound($response);
        }
        return Response::success($response);
    }

    /**
     * Metodo responsavel por armazenar uma URL abreviada no banco de dados após validar a URL de entrada e gerar
     * uma slug único.
     *
     * @param Request usado para recuperar a entrada de URL do usuário.
     *
     *  Se validação falhar, uma resposta JSON com as mensagens de erro de validação será retornada.
     * Se o bloco try for bem-sucedido, uma resposta JSON com o objeto Shortener recém-criado será
     * devolvida. Se uma exceção for capturada, uma resposta JSON com um status de sucesso falso e o erro
     * mensagem é retornada.
     */
    public function store(ShortenerRequest $request)
    {
        $response = $this->shortenerServices->createShortcut($request->url);
        if(!$response['success']){
            return Response::notFound($response);
        }
        return Response::created($response);
    }


    /**
     * Método resposável por recuperar todos os links encurtados e verifica se eles expiraram, retornando um JSON
     * resposta.
     *
     * Uma resposta JSON contendo todos os registros do modelo "Shortener", com um
     * atributo "expirado" adicional adicionado a cada registro com base em sua "data_de_expiração"
     * atributo é menor que a data e hora atuais.
     */
    public function show()
    {
        $response = $this->shortenerServices->get();
        if(!$response['success']){
            return Response::notFound($response);
        }
        return Response::success($response);
    }

    /**
     * Método responsável por reativar um link encurtado atualizando seu slug e data de validade
     *
     * @param $id O parâmetro "id" é o identificador exclusivo de um registro do Shortener no banco de dados. Isto
     * é usado para recuperar o registro e atualizar suas propriedades ao reativar um link.
     *
     * retorna uma resposta JSON com um status de sucesso e uma mensagem de erro
     * ou os dados do link reativado.
     */
    public function reactivate($id)
    {
        $response = $this->shortenerServices->reactivate($id);
        if(!$response['success']){
            return Response::notFound($response);
        }
        return Response::success($response);
    }

    /**
     * Método responsável por desabiltar um link encurtado atualizando seu slug e data de validade
     *
     * @param $id O parâmetro "id" é o identificador exclusivo de um registro do Shortener no banco de dados. Isto
     * é usado para recuperar o registro e atualizar suas propriedades ao desabiltar um link.
     *
     * retorna uma resposta JSON com um status de sucesso e uma mensagem de erro
     * ou os dados do link desabilitado.
     */
    public function disable($id)
    {
        $response = $this->shortenerServices->disable($id);
        if(!$response['success']){
            return Response::notFound($response);
        }
        return Response::success($response);
    }






}
