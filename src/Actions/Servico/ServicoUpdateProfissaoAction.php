<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoUpdateProfissaoDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MinIRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoProfissaoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class ServicoUpdateProfissaoAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(ServicoUpdateProfissaoDTO $dto)
    {
        $data = $dto->toArray();

        DB::beginTransaction();
        try{
            $servicoId = $data['tb_servico_idtb_servico'];
            $contratanteId = $data['tb_servico_tb_contratante_idtb_contratante'];
            $userId = $data['tb_servico_tb_contratante_tb_user_idtb_user'];
            $profissoes = $data['tb_profissoes_idtb_profissoes'];
            $servicoProfissaoRepository = new ServicoProfissaoRepository();

            foreach($profissoes as $profissaoId)
            {   
                
                $profissaoId = array_column($profissoes, 'id');
                $servicoProfissaoRepository->updateServicoProfissoes($servicoId, $profissaoId, $userId, $contratanteId);
            }
            DB::commit();


        }catch (\Exception $exception) {
            DB::rollback();
            throw new DatabaseInsertException(
                "Erro ao atualizar serviço: " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }

    }
}

?>