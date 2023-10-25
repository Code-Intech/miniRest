<?php

namespace MiniRest\Actions\Portifolio;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\AlbumPhotoNotFoundException;
use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;

class DeleteAlbumPortifolioUploadAction
{
    public function __construct()
    {
    }

    /**
     * @throws PrestadorNotFoundException|PortifolioPrestadorNotFoundException
     */
    public function execute(PortifolioRepository $portifolioRepository, $albumId, $userId)
    {
        try {
            $prestadorId = (new PrestadorRepository())->getPrestadorId($userId);
        } catch (ModelNotFoundException) {
            throw new PrestadorNotFoundException();
        }

        try {
            $portifolioRepository->deleteAlbum($albumId, $prestadorId, $userId);
        } catch (PortifolioPrestadorNotFoundException|ModelNotFoundException $e) {
            throw new PortifolioPrestadorNotFoundException();
        }

    }
}