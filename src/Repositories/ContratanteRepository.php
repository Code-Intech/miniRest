<?php

namespace MiniRest\Repositories;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Models\Contratante;
use MiniRest\Models\Servico\Servico;

class ContratanteRepository
{
    private Contratante $contratante;
    private Servico $servico;

    public function __construct()
    {
        $this->contratante = new Contratante();
        $this->servico = new Servico();
    }

    /**
     * @throws DatabaseInsertException
     */
    public function storeContratante(int $userId)
    {
        $id = $this->contratante
            ->firstOrCreate(["tb_user_idtb_user"=> $userId],
                [
                    'tb_user_idtb_user' => $userId
                ]
            );
        
        if($id->idtb_contratante == null)
        {
            $id = $this->getContratanteIdByUserId($userId);
        }
        
        return $id;
    }

    public function getContratanteIdByUserId(int $userId)
    {
        $contratante = $this->contratante->where('tb_user_idtb_user', $userId)->first();
        if ($contratante) {
            return $contratante->idtb_contratante;
        }
    }

    public function getContratanteByServicoId(int $servicoId)
    {
        $contratante = $this->servico->where('idtb_servico', $servicoId)->first();
        if($contratante)
        {
            return $contratante->tb_contratante_idtb_contratante;
        }
    }

    public function getUserByServicoId(int $servicoId)
    {
        $user = $this->servico->where('idtb_servico', $servicoId)->first();
        if($user)
        {
            return $user->tb_contratante_tb_user_idtb_user;
        }
    }
}


?>