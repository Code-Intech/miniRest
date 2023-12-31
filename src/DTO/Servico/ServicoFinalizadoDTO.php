<?php

namespace miniRest\DTO\Servico;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class ServicoFinalizadoDTO implements DTO
{   
    private $servicoId;
    private $data;
    private $tb_proposta_idtb_proposta;

    public function __construct(Request $request, $data, $tb_proposta_idtb_proposta, $servicoId)
    {   
        $this->servicoId = $servicoId;
        $this->data = $data;
        $this->tb_proposta_idtb_proposta = $tb_proposta_idtb_proposta;
    }

    function toArray(): array
    {
        return [
            'idtb_sv_finalizado' => $this->servicoId,
            'Data' => $this->data,
            'tb_proposta_idtb_proposta' => $this->tb_proposta_idtb_proposta
        ];
    }
}