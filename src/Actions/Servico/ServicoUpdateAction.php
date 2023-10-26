<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoUpdateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MinIRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoRepository;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Models\Servico\Servico;
use MiniRest\Repositories\Servico\ServicoHabilidadeRepository;
use MiniRest\Repositories\Servico\ServicoProfissaoRepository;

class ServicoUpdateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(ServicoUpdateDTO $servicoUpdateDTO)
    {
        $data = $servicoUpdateDTO->toArray();

        DB::beginTransaction();
        try{
            $servicoRepository = new ServicoRepository(new Servico());
            $servico =  $servicoRepository->updateServico($data);
            $servicoId = $data['idtb_servico'];
            $userId = $data['tb_contratante_tb_user_idtb_user'];
            $contratanteId = $data['tb_contratante_idtb_contratante'];

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