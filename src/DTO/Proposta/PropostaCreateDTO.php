<?php

namespace MiniRest\DTO\Proposta;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;


class PropostaCreateDTO implements DTO
{
    private Request $request;
    private $valor_proposta;
    private $comentario;
    private $data_proposta;
    private $userId;
    private $contratanteId;
    private $prestadorId;
    private $servicoId;
    private $contratanteUser;

    public function __construct(Request $request, $userId, $contratanteId, $prestadorId, $servicoId, $contratanteUser)
    {
        $this->request = $request;
        $this->valor_proposta = $request->json('Valor_Proposta');
        $this->comentario = $request->json('Comentario');
        $this->data_proposta = $request->json('Data_Proposta');
        $this->userId = $userId;
        $this->contratanteId = $contratanteId;
        $this->prestadorId = $prestadorId;
        $this->servicoId = $servicoId;
        $this->contratanteUser = $contratanteUser;
    }

    public function toArray(): array
    {
        return [
            'Valor_Proposta' => $this->valor_proposta,
            'Comentario' => $this->comentario,
            'Data_Proposta' => $this->data_proposta,
            'tb_servico_idtb_servico' => $this->servicoId,
            'tb_servico_tb_contratante_idtb_contratante' => $this->contratanteId,
            'tb_servico_tb_contratante_tb_user_idtb_user' => $this->contratanteUser,
            'tb_prestador_idtb_prestador' => $this->prestadorId,
            'tb_prestador_tb_user_idtb_user' => $this->userId
        ];
    }
}

?>
