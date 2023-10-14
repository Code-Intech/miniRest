<?php

namespace MiniRest\Repositories\Prestador;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Prestador\Prestador;
use MiniRest\Models\Prestador\PrestadorHabilidades;
use MiniRest\Models\Prestador\PrestadorProfissao;

class PrestadorSkillsRepository
{
    private PrestadorHabilidades $prestadorHabilidades;

    public function __construct()
    {
        $this->prestadorHabilidades = new PrestadorHabilidades();
    }

    /**
     * @throws DatabaseInsertException
     */
    public function storePrestadorSkills(int $userId, int $prestadorId, int $data): void
    {
        $this->prestadorHabilidades
            ->create(
                [
                    'tb_habilidades_idtb_habilidades' => $data,
                    'tb_prestador_tb_user_idtb_user' => $userId,
                    'tb_prestador_idtb_prestador' => $prestadorId
                ]
            );

    }

}