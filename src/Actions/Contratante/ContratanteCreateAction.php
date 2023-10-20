<?php

namespace MiniRest\Actions\Contratante;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\ContratanteRepository;
use Illuminate\Database\Capsule\Manager as DB;

class ContratanteCreateAction
{
    private ContratanteRepository $contratanteRepository;

    public function __construct(ContratanteRepository $contratanteRepository)
    {
        $this->contratanteRepository = $contratanteRepository;
    }

    /**
     * @throws DatabaseInsertException
     */

    public function execute(int $userId)
    {
        try{
            return $this->contratanteRepository->storeContratante($userId);

        }catch(\Exception $exception){
            DB::rollback();
            throw new DatabaseInsertException(
                "error ao inserir o contratante " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }
}

?>