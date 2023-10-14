<?php

namespace MiniRest\Repositories\Prestador;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Prestador\Prestador;
use MiniRest\Models\Prestador\PrestadorApresentacao;

class ApresentacaoRepository
{
    private PrestadorApresentacao $apresentacao;

    public function __construct()
    {
        $this->apresentacao = new PrestadorApresentacao();
    }

    /**
     * @throws DatabaseInsertException
     */
    public function storeApresentacao(int $userId, int $prestadorId, array $data)
    {
        $this->apresentacao
            ->firstOrCreate(
                ['tb_prestador_tb_user_idtb_user' => $userId],
                [
                    'Apresentacao' => $data['Apresentacao'],
                    'tb_prestador_tb_user_idtb_user' => $userId,
                    'tb_prestador_idtb_prestador' => $prestadorId
                ]
            );
    }

    public function updateApresentacao(int $userId, array $data): void
    {
        $this->apresentacao
            ->where('tb_prestador_tb_user_idtb_user', $userId)
            ->update(
                [
                    'Apresentacao' => $data['Apresentacao'],
                ]
            );
    }

}