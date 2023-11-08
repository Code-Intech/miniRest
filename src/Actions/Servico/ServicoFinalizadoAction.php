<?php

namespace MiniRest\Actions\Servico;

use MiniRest\Exceptions\DatabaseInsertException;
use MinIRest\Helpers\StatusCode\StatusCode;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Models\Servico\ServicoFinalizado;
use MiniRest\DTO\Servico\ServicoFinalizadoDTO;
use MinIRest\Repositories\Servico\ServicoRepository;

class ServicoFinalizadoAction
{
    /**
     * @throws DatabaseInsertException
     */

     public function execute(ServicoFinalizadoDTO $endDTO)
     {
        $data = $endDTO->toArray();

        DB::beginTransaction();
        try
        {
            $servicoRepository = new ServicoRepository(new ServicoFinalizado());
            $finaliza = $servicoRepository->finalizaServico($data);

            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            throw new DatabaseInsertException(
                "Erro ao finalizar serviço: " . $e->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
     }
}