<?php

namespace MiniRest\Repositories\Prestador;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Prestador\Prestador;
use MiniRest\Models\Prestador\PrestadorProfissao;

class PrestadorProfessionRepository
{
    private PrestadorProfissao $prestadorProfissao;

    public function __construct()
    {
        $this->prestadorProfissao = new PrestadorProfissao();
    }

    /**
     * @throws DatabaseInsertException
     */
    public function storePrestadorProfession(int $userId, int $prestadorId,array $data): void
    {
        $this->prestadorProfissao
            ->create(
                [
                    'Experiencia' => $data['experiencia'],
                    'tb_profissoes_idtb_profissoes' => $data['id'],
                    'tb_prestador_tb_user_idtb_user' => $userId,
                    'tb_prestador_idtb_prestador' => $prestadorId
                ]
            );

    }

    /**
     * @throws DatabaseInsertException
     */
    public function updatePrestadorProfession(int $userId, int $prestadorId, array $data): void
    {

        $this->prestadorProfissao
            ->where('tb_prestador_idtb_prestador', $prestadorId)
            ->where('tb_prestador_tb_user_idtb_user', $userId)
            ->delete();

        foreach ($data as $dataJob) {
            $this->storePrestadorProfession($userId, $prestadorId, $dataJob);
        }

    }

}