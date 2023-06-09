<?php

namespace App\Repositories;

use App\Models\Shortener;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class ShortenerRepository
{
    /**
     * @var Shortener
     */
    protected $entity;

    /**
     * Função contrutora
     *
     * @param Shortener $shortener
     */
    public function __construct(Shortener $shortener)
    {
        $this->entity = $shortener;
    }
    /**
     * Método responsável por recuperar todos os links conforme paginação
     *
     * @return array
     */
    public function getAll(): array
    {
        try {
            $perPage = request('perPage', 2);
            $orderBy = request('orderBy', 'DESC');
            $filter = request('filter', '');
            $now = new Carbon();
            $links = $this->entity
                ->select('*', DB::raw('IF(expiration_date < "' . $now . '", true, false) AS expired'))
                ->where('url', 'like', '%' . $filter . '%')
                ->orderBy('url', $orderBy)
                ->paginate($perPage);

            return [
                'success' => true,
                'shortcuts' => $links,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }
    /**
     * Método responsável por recuperar todos os links desabilitados conforme paginação
     *
     * @return array
     */
    public function getAllDisabled(): array
    {
        try {
            $perPage = request('perPage', 2);
            $orderBy = request('orderBy', 'DESC');
            $filter = request('filter', '');
            $now = new Carbon();
            $links = $this->entity
                ->select('*', DB::raw('IF(expiration_date < "' . $now . '", true, false) AS expired'), DB::raw('true AS disabled'))
                ->withTrashed()
                ->where([
                    ['url', 'like' , '%' . $filter . '%'],
                    [ 'deleted_at' , '!=', null]
                ])
                ->orderBy('url', $orderBy)
                ->paginate($perPage);

            return [
                'success' => true,
                'shortcuts' => $links,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }
    /**
     * Método responsável com adicionar um link encurtado na base de dados
     *
     * @param string $url
     * @param string $slug
     * @return array
     */
    public function createNewShortcut(string $url, string $slug): array
    {
        try {
            $linkExists = $this->entity->where('url', $url)->exists();
            if ($linkExists) {
                return [
                    'success' => false,
                    'message' => 'Este link já está encurtado'
                ];
            }
            $link = new Shortener;
            $link->url = $url;
            $link->slug = $slug;
            $link->expiration_date = now()->addDays(7);
            $link->save();

            return [
                'success' => true,
                'data' => $link
            ];
        } catch (\Exception $e) {
            Log::error($e);
            return [
                'success' => false,
                'error' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Método responsável por devolver o link da base de dados conforme slug
     *
     * @param string $slug
     * @return array
     */
    public function getShortcutBySlug(string $slug): array
    {
        try {
            $link = $this->entity->where('slug', $slug)
                ->where('expiration_date', '>', now())
                ->first();

            if (!$link) {
                return [
                    'success' => false,
                    'message' => 'Página não encontrada'
                ];
            }

            return [
                'success' => true,
                'message' => 'Página encontrada',
                'data' => $link
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }
    /**
     * Método responsável por desabiliar um link
     *
     * @param string $id
     * @return array
     */
    public function disableShortcut(string $id): array
    {

        try {
            $link = $this
                ->entity
                ->where('id', $id)
                ->first();

            if (!$link) {
                return [
                    'success' => false,
                    'message' => 'Esse link não pode ser desabilitado!'
                ];
            }

            $link->delete();

            return [
                'success' => true,
                'data' => ['id' => $link->id],
                'message' => "O link {$link->slug} foi desabilitado com sucesso!"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Método responsável por reativar um link expirado com um novo slug
     *
     * @param string $id
     * @param string $slug
     * @return array
     */
    public function reactivateShortcut(string $id, string $slug): array
    {
        try {
            $link = $this->entity->where('id', $id)
                ->where('expiration_date', '<=', now())
                ->first();

            if (!$link) {
                return [
                    'success' => false,
                    'message' => 'Esse link não pode ser reativado!'
                ];
            }

            $link->slug = $slug;
            $link->expiration_date = now()->addDays(7);
            $link->save();

            return [
                'success' => true,
                'message' => 'Link reativado com successo!',
                'data' => $link
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }


}
