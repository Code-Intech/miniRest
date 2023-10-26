<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoUpdateHabilidadeDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoHabilidadeRepository;
use Illuminate\Database\Capsule\Manager as DB;

class ServicoUpdateHabilidadeAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(ServicoUpdateHabilidadeDTO $dto)
    {
        $data = $dto->toArray();

        DB::beginTransaction();
        try{
            $servicoId = $data['tb_servico_idtb_servico'];
            $contratanteId = $data['tb_servico_tb_contratante_idtb_contratante'];
            $userId = $data['tb_servico_tb_contratante_tb_user_idtb_user'];
            $habilidades = $data['tb_habilidades_idtb_habilidades'];
            $servicoHabilidadeRepository = new ServicoHabilidadeRepository();

            foreach($habilidades as $habilidadeId)
            {
                $habilidadeId = array_column($habilidades, 'id');
                $servicoHabilidadeRepository->updateServicoHabilidades($servicoId, $habilidadeId, $userId, $contratanteId);

            }

            DB::commit();
            
        }catch (\Exception $exception){
            DB::rollback();
            throw new DatabaseInsertException(
                "Erro ao atualizar serviço: " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }
}



?>
