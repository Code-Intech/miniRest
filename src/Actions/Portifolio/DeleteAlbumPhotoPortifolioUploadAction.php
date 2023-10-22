<?php

namespace MiniRest\Actions\Portifolio;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\AlbumPhotoNotFoundException;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;

class DeleteAlbumPhotoPortifolioUploadAction
{
    public function __construct()
    {
    }

    /**
     * @throws PrestadorNotFoundException|AlbumPhotoNotFoundException
     */
    public function execute(PortifolioRepository $portifolioRepository, $photoId, $userId)
    {
        try {
            $prestadorId = (new PrestadorRepository())->getPrestadorId($userId);
        } catch (ModelNotFoundException) {
            throw new PrestadorNotFoundException();
        }

        try {
            $portifolioRepository->deleteAlbumPhoto($photoId, $prestadorId);
        } catch (AlbumPhotoNotFoundException) {
            throw new AlbumPhotoNotFoundException();
        }

    }
}