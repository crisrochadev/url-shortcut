<?php

namespace App\Http\Controllers;

use Hashids\Hashids;

use App\Models\Shortener;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Log;

class ShortenerController extends Controller
{

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
        $link = Shortener::where('slug', $slug)
            ->where('expiration_date', '>', now())
            ->where('disable', '=', false)
            ->first();
        if (!$link) {
            return redirect('http://localhost:8080/404');
        }

        return redirect()->away($link->url);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Esta função armazena uma URL abreviada no banco de dados após validar a URL de entrada e gerar
     * uma slug único.
     *
     * @param Request usado para recuperar a entrada de URL do usuário.
     *
     *  Se validação falhar, uma resposta JSON com as mensagens de erro de validação será retornada.
     * Se o bloco try for bem-sucedido, uma resposta JSON com o objeto Shortener recém-criado será
     * devolvida. Se uma exceção for capturada, uma resposta JSON com um status de sucesso falso e o erro
     * mensagem é retornada.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'url' => 'required|url|max:255'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages());
        }

        try {
            do {
                $id = uniqid();
                $slug = $this->hash($id);
            } while (Shortener::where('slug', $slug)->exists());

            $link = new Shortener;
            $link->url = $request->url;
            $link->slug = $slug;
            $link->expiration_date = now()->addDays(7);
            $link->save();

            return response()->json($link);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function show()
    {
        $links = Shortener::all();
        $now = now();

        foreach ($links as $link) {
            if ($link->expiration_date < $now) {
                $link->expired = true;
            } else {
                $link->expired = false;
            }
        }

        return response()->json($links);
    }

    public function reactivate($id)
    {
        $link = Shortener::where('id', $id)
            ->where('expiration_date', '<', now())
            ->where('disable', '=', false)
            ->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link not found or cannot be reactivated']);
        }

        $link->slug = $this->hash($id);
        $link->expiration_date = now()->addDays(7);
        $link->save();

        return response()->json(['success' => true, 'data' => $link]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function disable($id)
    {
        $link = Shortener::where('id', $id)
            ->where('disable', '=', false)
            ->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link not found or cannot be disabled']);
        }

        $link->disable = true;
        $link->save();

        return response()->json(['success' => true, 'message' => "The link {$link->slug} was disabled successfully"]);
    }

    public function enable($id)
    {
        $link = Shortener::where('id', $id)
            ->where('expiration_date', '>', now())
            ->where('disable', '=', true)
            ->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link not found or cannot be enabled']);
        }

        $link->disable = false;
        $link->save();

        return response()->json(['success' => true, 'message' => "The link {$link->slug} was enabled successfully"]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shortener $shortener)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shortener $shortener)
    {
        //
    }

    private function hash($id)
    {
        $hashids = new Hashids(env('HASHIDS_SALT'), env('HASHIDS_MIN_LENGTH'));
        return $hashids->encodeHex($id);
    }

}
