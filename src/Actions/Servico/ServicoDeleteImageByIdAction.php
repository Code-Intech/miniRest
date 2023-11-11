<?php

namespace MiniRest\Actions\Servico;

use MiniRest\Exceptions\AccessNotAllowedException;
use MiniRest\Exceptions\ImagesNotFoundException;
use MiniRest\Repositories\Servico\ServicoRepository;

class ServicoDeleteImageByIdAction
{
    /**
     * @throws AccessNotAllowedException|ImagesNotFoundException
     */
    public function execute(int $imageId, int $userId, ServicoRepository $servicoRepository)
    {
        $servicoRepository->getImageById($imageId);
        $servicoRepository->verifyServiceOwner($userId, $imageId);
        $servicoRepository->deleteImageByImageId($imageId, $userId);
    }
}