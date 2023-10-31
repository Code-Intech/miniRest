<?php

namespace MiniRest\Actions\Proposta;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\DTO\Proposta\PropostaCreateDTO;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Models\Proposta\Proposta;
use MiniRest\Repositories\Proposta\PropostaRepository;


class PropostaCreateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(PropostaCreateDTO $propostaDTO)
    {
        $data = $propostaDTO->toArray();

        DB::beginTransaction();
        try
        {
            $propostaRepository = new PropostaRepository(new Proposta());
            $proposta = $propostaRepository->storeProposta($data);
            $propostaId = $proposta->idtb_proposta;

            DB::commit();
            return $propostaId;
        }
        catch (\Exception $exception) 
        {
            DB::rollback();
            throw new DatabaseInsertException(
                "Erro ao inserir a proposta: " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }

}

?>