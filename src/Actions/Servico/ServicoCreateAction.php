<?php

namespace MiniRest\Actions\Servico;

use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Servico\ServicoRepository;
use MiniRest\Models\Servico\Servico;
use Illuminate\Database\Capsule\Manager as DB;

class ServicoCreateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(ServicoCreateDTO $servicoDTO)
    {
        $data = $servicoDTO->toArray();

        DB::beginTransaction();
        try {

            (new ServicoRepository(new Servico()))->storeServico($data);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollback();
            throw new DatabaseInsertException(
                "Erro ao inserir o serviço: " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }
}
