<?php

namespace MiniRest\DTO\Portifolio;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class PortifolioCreateDTO implements DTO
{
    private array $portifolioCover;
    private array $portifolioPhotos;
    private string $title;
    private string $description;


    public function __construct(
        protected Request $request,
    )
    {
        $this->portifolioCover = $this->request->files('portifolioCover');
        $this->portifolioPhotos = $this->request->files('portifolioPhotos');
        $this->description = $this->request->post('description');
        $this->title = $this->request->post('title');
    }

    public function toArray(): array
    {
        return [
            'Capa' => $this->portifolioCover,
            'Img' => $this->portifolioPhotos,
            'Descricao' => $this->description,
            'Titulo' => $this->title,
        ];
    }
}