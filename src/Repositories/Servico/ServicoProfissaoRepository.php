<?php

namespace MiniRest\Repositories\Servico;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Models\Servico\ServicoProfissao;

class ServicoProfissaoRepository
{
    private ServicoProfissao $servicoProfissao;

    public function __construct()
    {
        $this->servicoProfissao = new ServicoProfissao();
    }

    /**
     * @throws DatabaseInsertException
     */
    public function storeServicoProfissao(int $servicoId, array $profissoes, int $userId, int $contratanteId): void
    {   
        foreach($profissoes as $profissaoId){
            $this->servicoProfissao->create([
                'tb_servico_idtb_servico' => $servicoId,
                'tb_servico_tb_contratante_idtb_contratante' => $contratanteId,
                'tb_servico_tb_contratante_tb_user_idtb_user' => $userId,
                'tb_profissoes_idtb_profissoes' => $profissaoId,
            ]);
        }
    }


    public function updateServicoProfissoes(int $servicoId, array $profissoes, int $userId, int $contratanteId): void
    {
        $this->servicoProfissao->where('tb_servico_idtb_servico', $servicoId)->delete();
        $this->storeServicoProfissao($servicoId, $profissoes, $userId, $contratanteId);
    }

}

?>
