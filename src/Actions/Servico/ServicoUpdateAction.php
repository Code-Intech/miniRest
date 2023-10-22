<?php

namespace MiniRest\Actions\Servico;

use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoRepository;

class ServicoUpdateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(int $userId, ServicoCreateDTO $servicoCreateDTO)
    {
        $servicoData = $servicoCreateDTO->toArray();

        DB::beginTransaction();
        try{
            (new ServicoRepository())->updateServico($userId, $servicoData);
            DB::commit();
        }catch(\Exception $exception){
            DB::rollBack();
            throw new DatabaseInsertException(
                "Erro ao atualizar serviço." . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }
}

?>