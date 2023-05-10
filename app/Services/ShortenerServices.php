<?php

namespace App\Services;

use App\Repositories\ShortenerRepository;
use Hashids\Hashids;
use Carbon\Carbon;


class ShortenerServices
{
    /**
     *
     * @var ShortenerRepository
     */
    protected $repository;

    public function __construct(ShortenerRepository $shortenerRepository)
    {
        $this->repository = $shortenerRepository;
    }
    /**
     * Método responsável pode trazer os links do repositório
     *
     * @return array
     */
    public function get(): array
    {
        return $this->repository->getAll();
    }
       /**
     * Método responsável pode trazer os links desabilitados do repositório
     *
     * @return array
     */
    public function getDisabled(): array
    {
        return $this->repository->getAllDisabled();
    }
    /**
     * Método responsável por enviar uma url para o repositorio criar um link e devolver a resposta.
     *
     * @param string $url
     * @return array
     */
    public function createShortcut(string $url): array
    {

        return $this->repository->createNewShortcut($url, $this->hash());
    }

    /**
     * Método responsável por trazer o link pelo seu id
     *
     * @param string $slug
     * @return array
     */
    public function getBySlug(string $slug): array
    {
        return $this->repository->getShortcutBySlug($slug);
    }

    /**
     * Método responsável por enviar um id para o repositorio reativar um link expirado com um novo
     * e devolver a resposta.
     *
     * @param string $id
     * @return array
     */
    public function reactivate(string $id): array
    {
        return $this->repository->reactivateShortcut($id, $this->hash());
    }
    /**
     * Método responsável por enviar um id para o repositório desabiliar um link e devolver a resposta.
     *
     * @param string $id
     * @return array
     */
    public function disable(string $id): array
    {
        return $this->repository->disableShortcut($id);
    }

    /**
     * Método responsável por criar um slug, usando Hashids
     *
     * @return string
     */
    private function hash(): string
    {
        do {
            $id = uniqid();
            $hashids = new Hashids(env('HASHIDS_SALT'), env('HASHIDS_MIN_LENGTH'));
            $hash = $hashids->encodeHex($id);
            $link = $this->repository->getShortcutBySlug($hash);
        } while ($link['success'] === true);
        return $hash;
    }


}
