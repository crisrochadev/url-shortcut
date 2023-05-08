<?php

namespace App\Repositories;

use App\Models\Shortener;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Log;

class ShortenerRepository
{
    protected $entity;
    public function __construct(Shortener $shortener)
    {
        $this->entity = $shortener;
    }
    public function getAll()
    {
        try {
            $now = new Carbon();
            $links = $this->entity
                ->select('*', DB::raw('IF(expiration_date < "' . $now . '", true, false) AS expired'))
                ->paginate(20);

            return [
                'success' => true,
                'data' => $links
            ];
        } catch (\Exception $e) {
            return [
                'success' => true,
                'error_message' => $e->getMessage()
            ];
        }
    }
    public function createNewShortcut(string $url, string $slug)
    {
        try {
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
                'error_message' => $e->getMessage()
            ];
        }
    }
    public function getShortcutBySlug(string $slug)
    {
        try {
            $link = $this->entity->where('slug', $slug)
                ->where('expiration_date', '>', now())
                ->where('disable', '=', false)
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
                'error_message' => $e->getMessage()
            ];
        }
    }
    public function disableShortcut(string $id)
    {

        try {
            $link = $this->entity->where('id', $id)
                ->where('disable', '=', true)
                ->first();
            if (!$link) {
                return [
                    'success' => false,
                    'message' => 'Esse link não pode ser desativado!'
                ];
            }

            $link->disable = true;
            $link->expiration_date = new Carbon;
            $link->save();

            return [
                'success' => true,
                'data' => ['id' => $link->id],
                'message' => "O link {$link->slug} foi desativado com sucesso!"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error_message' => $e->getMessage()
            ];
        }
    }

    public function reactivateShortcut(string $id, string $slug)
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
                'error_message' => $e->getMessage()
            ];
        }
    }

}
