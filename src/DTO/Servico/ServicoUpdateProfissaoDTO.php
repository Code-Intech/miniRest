<?php

namespace MiniRest\DTO\Servico;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class ServicoUpdateProfissaoDTO implements DTO
{  
    private Request $request;
    private $servicoId;
    private $contratanteId;
    private $userId;
    private $profissoes;

    public function __construct(Request $request, $servicoId, $contratanteId, $userId, $profissoes)
    {
        $this->request = $request;
        $this->servicoId = $servicoId;
        $this->contratanteId = $contratanteId; 
        $this->userId = $userId;  
        $this->profissoes = $profissoes;
    }

    function toArray(): array
    {
        return [
            'tb_servico_idtb_servico' => $this->servicoId,
            'tb_servico_tb_contratante_idtb_contratante' => $this->contratanteId,
            'tb_servico_tb_contratante_tb_user_idtb_user' => $this->userId,
            'tb_profissoes_idtb_profissoes' => $this->profissoes,
        ];
    }
}

?>