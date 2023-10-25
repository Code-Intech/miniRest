<?php

namespace MiniRest\DTO\Servico;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class ServicoUploadImageDTO implements DTO
{
    private array $servicoImages;
    private $servicoId;
    private $contratanteId;
    private $userId;

    public function __construct(protected Request $request, $servicoId, $contratanteId, $userId)
    {
        $this->servicoImages = $this->request->files('image');
        $this->servicoId = $servicoId;
        $this->contratanteId = $contratanteId;
        $this->userId = $userId;
    }

    public function toArray(): array
    {
        return [
            'IMG' => $this->servicoImages,
            'tb_servico_idtb_servico' => $this->servicoId,
            'tb_servico_tb_contratante_idtb_contratante' => $this->contratanteId,
            'tb_servico_tb_contratante_tb_user_idtb_user' => $this->userId,
        ];
    }
}

?>