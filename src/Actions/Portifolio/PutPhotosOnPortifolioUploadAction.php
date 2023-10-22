<?php

namespace MiniRest\Actions\Portifolio;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\DTO\Portifolio\PortifolioCreateDTO;
use MiniRest\DTO\Portifolio\PortifolioPutPhotoDTO;
use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Exceptions\PortifolioUpdateNotAllowedException;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;

class PutPhotosOnPortifolioUploadAction
{
    public function __construct()
    {
    }

    /**
     * @throws UploadErrorException|PrestadorNotFoundException|PortifolioPrestadorNotFoundException|PortifolioUpdateNotAllowedException
     */
    public function execute(PortifolioPutPhotoDTO $putPhotoDTO, int $userId, $portifolioId)
    {
        try {

            $photo = UUIDFileName::uuidFileName($putPhotoDTO->toArray()['Img']['name']);
            $portifolioRespository = new PortifolioRepository();

            try {
                $portifolioRespository->verifyPortifolioOwner($userId, $portifolioId);
            } catch (ModelNotFoundException) {
                throw new PortifolioUpdateNotAllowedException();
            }

            try {
                $portifolioRespository->verifyPortifolioId($portifolioId);
            } catch (ModelNotFoundException) {
                throw new PortifolioPrestadorNotFoundException();
            }

            try {
                $prestadorId = (new PrestadorRepository())->getPrestadorId($userId);
            } catch (ModelNotFoundException) {
                throw new PrestadorNotFoundException();
            }

            $upload = new S3Storage(new PublicAcl());

            $portifolioRespository->storeGalleryItens(
                $photo,
                $portifolioId,
                $prestadorId,
                $userId
            );

            $upload->upload(
                'portifolio/' . $photo,
                $putPhotoDTO->toArray()['Img']['tmp_name']
            );

        } catch (\PDOException $e) {
            throw new UploadErrorException($e->getMessage());
        }

    }
}