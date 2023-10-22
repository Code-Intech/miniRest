<?php

namespace MiniRest\DTO\Portifolio;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class PortifolioUpdateDTO implements DTO
{
    private array $portifolioCover;
    private string $title;
    private string $description;


    public function __construct(
        protected Request $request,
    )
    {
        $this->portifolioCover = $this->request->files('portifolioCover');
        $this->description = $this->request->post('description');
        $this->title = $this->request->post('title');
    }

    public function toArray(): array
    {
        return [
            'Capa' => $this->portifolioCover,
            'Descricao' => $this->description,
            'Titulo' => $this->title,
        ];
    }
}