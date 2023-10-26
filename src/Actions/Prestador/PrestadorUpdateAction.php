<?php

namespace MiniRest\Actions\Prestador;

use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\DTO\Prestador\PrestadorCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Prestador\Prestador;
use MiniRest\Repositories\Prestador\ApresentacaoRepository;
use MiniRest\Repositories\Prestador\PrestadorProfessionRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Repositories\Prestador\PrestadorSkillsRepository;

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

            $prestador = Prestador::where('tb_user_idtb_user', $userId)->firstOrFail();
            $prestadorId = $prestador->idtb_prestador;

            (new PrestadorRepository())->updatePrestador($userId, $prestadorData);
            (new ApresentacaoRepository())->updateApresentacao($userId, $prestadorData);

            $jobs = [];
            foreach ($prestadorData['tb_prestador_profissao'] as $profissao) {
                $jobs[] = [
                    'id' => $profissao['id'],
                    'experiencia' => $profissao['experiencia']
                ];
            }

            $skill = [];
            foreach ($prestadorData['tb_habilidades_idtb_habilidades'] as $prestadorSkill) {
                $skill[] = [
                    'tb_habilidades_idtb_habilidades' => $prestadorSkill,
                ];
            }

            (new PrestadorProfessionRepository())->updatePrestadorProfession($userId, $prestadorId, $jobs);

            (new PrestadorSkillsRepository())->updatePrestadorSkills($userId, $prestadorId, $skill);

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