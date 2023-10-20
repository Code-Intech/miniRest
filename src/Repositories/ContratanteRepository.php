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
        
        if($id->id == null)
            throw new DatabaseInsertException(
                'error ao fazer insert, contratante já foi cadastrado.',
                StatusCode::SERVER_ERROR
            );
        
        return $id->id;
    }
}


?>