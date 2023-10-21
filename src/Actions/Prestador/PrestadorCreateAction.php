<?php

namespace MiniRest\Actions\Prestador;

use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\DTO\Prestador\PrestadorCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Repositories\Prestador\ApresentacaoRepository;
use MiniRest\Repositories\Prestador\PrestadorProfessionRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Repositories\Prestador\PrestadorSkillsRepository;

class PrestadorCreateAction
{
    /**
     * @throws DatabaseInsertException
     */
    public function execute(int $userId, PrestadorCreateDTO $prestadorCreateDTO)
    {
        $prestadorData = $prestadorCreateDTO->toArray();

        DB::beginTransaction();
        try {
            $prestadorId = (new PrestadorRepository())->storePrestador($userId, $prestadorData);
            (new ApresentacaoRepository())->storeApresentacao($userId, $prestadorId, $prestadorData);


            foreach ($prestadorData['tb_prestador_profissao'] as $profissao)
            {
                (new PrestadorProfessionRepository())->storePrestadorProfession($userId, $prestadorId, $profissao);
            }

            foreach ($prestadorData['tb_habilidades_idtb_habilidades'] as $habilidades)
            {
                (new PrestadorSkillsRepository())->storePrestadorSkills($userId, $prestadorId, $habilidades);
            }

            DB::commit();
            return $prestadorId;

        } catch (\Exception $exception) {
            DB::rollback();
            throw new DatabaseInsertException(
                "error ao inserir o prestador " . $exception->getMessage(),
                StatusCode::SERVER_ERROR
            );
        }
    }
}