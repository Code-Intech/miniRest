<?php

namespace MiniRest\Actions\Prestador;

use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\DTO\Prestador\PrestadorCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Prestador\ApresentacaoRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;

class PrestadorUpdateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(int $userId, PrestadorCreateDTO $prestadorCreateDTO)
    {
        $prestadorData = $prestadorCreateDTO->toArray();

        DB::beginTransaction();
        try {
            (new PrestadorRepository())->updatePrestador($userId, $prestadorData);
            (new ApresentacaoRepository())->updateApresentacao($userId, $prestadorData);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw new DatabaseInsertException(
                "error ao inserir o prestador " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }
}