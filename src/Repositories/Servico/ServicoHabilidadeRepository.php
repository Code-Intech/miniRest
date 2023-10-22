<?php

namespace MiniRest\Repositories\Servico;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Models\Servico\ServicoHabilidade;

class ServicoHabilidadeRepository
{
    private ServicoHabilidade $servicoHabildade;

    public function __construct()
    {
        $this->servicoHabildade = new ServicoHabilidade();
    }

    /**
     * @throws DatabaseInsertException
     */

     public function storeServicoHabilidade(int $servicoId, int $contratanteId, $userId, $habilidades):void
     {
        foreach($habilidades as $habilidadeId){
            $this->servicoHabildade->create([
                'tb_servico_idtb_servico' => $servicoId,
                'tb_servico_tb_contratante_idtb_contratante' => $contratanteId,
                'tb_servico_tb_contratante_tb_user_idtb_user' => $userId,
                'tb_habilidades_idtb_habilidades' => $habilidadeId,
            ]);
        }
     }
}
?>