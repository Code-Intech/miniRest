<?php

namespace MiniRest\Repositories;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Contratante;

class ContratanteRepository
{
    private Contratante $contratante;

    public function __construct()
    {
        $this->contratante = new Contratante();
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
}


?>