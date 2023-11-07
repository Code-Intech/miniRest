<?php

namespace miniRest\DTO\Servico;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class ServicoFinalizadoDTO implements DTO
{
    private $data;
    private $tb_proposta_idtb_proposta;

    public function __construct(Request $request, $data, $tb_proposta_idtb_proposta)
    {
        $this->data = $data;
        $this->tb_proposta_idtb_proposta = $tb_proposta_idtb_proposta;
    }

    function toArray(): array
    {
        return [
            'Data' => $this->data,
            'tb_proposta_idtb_proposta' => $this->tb_proposta_idtb_proposta
        ];
    }
}