<?php

namespace App\Services;

use App\Repositories\ShortenerRepository;
use Hashids\Hashids;
use Carbon\Carbon;


class ShortenerServices
{
    protected $repository;

    public function __construct(ShortenerRepository $shortenerRepository)
    {
        $this->repository = $shortenerRepository;
    }

    public function get()
    {
        return $this->repository->getAll();
    }
    public function createShortcut(string $url)
    {

        return $this->repository->createNewShortcut($url, $this->hash());
    }

    public function getBySlug(string $slug)
    {
        return $this->repository->getShortcutBySlug($slug);
    }

    public function reactivate(string $id)
    {
        return $this->repository->reactivateShortcut($id, $this->hash());
    }
    public function disable(string $id)
    {
        return $this->repository->disableShortcut($id);
    }
    private function hash()
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
