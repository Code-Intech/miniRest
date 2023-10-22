<?php

namespace MiniRest\Actions\Portifolio;

use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;

class PortifolioGetByIdAction
{
    /**
     * @throws PortifolioPrestadorNotFoundException
     */
    public function execute(PortifolioRepository $portifoliotRespository, int $portifolioId, string $baseUrl)
    {
        return $portifoliotRespository->getAlbumById($portifolioId, $baseUrl);
    }
}