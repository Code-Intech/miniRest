<?php

namespace MiniRest\Actions\Portifolio;

use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;

class GetPortifolioUserAction
{
    /**
     * @throws PortifolioPrestadorNotFoundException
     */
    public function execute(PortifolioRepository $portifoliotRespository, int $userId, string $baseUrl)
    {
        return $portifoliotRespository->getAll($userId, $baseUrl);
    }
}