<?php

namespace MiniRest\Repositories\Servico;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Models\Servico\ServicoHabilidade;

class ServicoHabilidadeRepository
{
    private ServicoHabilidade $servicoHabilidade;

    public function __construct()
    {
        $this->servicoHabilidade = new ServicoHabilidade();
    }

    /**
     * @throws DatabaseInsertException
     */

     public function storeServicoHabilidade(int $servicoId, int $contratanteId, $userId, $habilidades):void
     {
        foreach($habilidades as $habilidadeId){
            $this->servicoHabilidade->create([
                'tb_servico_idtb_servico' => $servicoId,
                'tb_servico_tb_contratante_idtb_contratante' => $contratanteId,
                'tb_servico_tb_contratante_tb_user_idtb_user' => $userId,
                'tb_habilidades_idtb_habilidades' => $habilidadeId,
            ]);
        }
     }

     public function updateServicoHabilidades(int $servicoId, array $habilidades, int $userId, int $contratanteId): void
    {
        $this->servicoHabilidade->where('tb_servico_idtb_servico', $servicoId)->delete();
        $this->storeServicoHabilidade($servicoId, $contratanteId, $userId, $habilidades);

    }
}
?>