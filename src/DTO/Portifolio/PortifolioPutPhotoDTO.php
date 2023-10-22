<?php

namespace MiniRest\DTO\Portifolio;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class PortifolioPutPhotoDTO implements DTO
{
    private array $portifolioPhotos;


    public function __construct(
        protected Request $request,
    )
    {
        $this->portifolioPhotos = $this->request->files('photo');
    }

    public function toArray(): array
    {
        return [
            'Img' => $this->portifolioPhotos,
        ];
    }
}