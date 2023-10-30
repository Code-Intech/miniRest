<?php

namespace MiniRest\Repositories\Proposta;

use MiniRest\Models\Proposta\Proposta;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use Illuminate\Database\Capsule\Manager as DB;


class PropostaRepository
{
    private $proposta;

    public function __construct()
    {
        $this->proposta = new Proposta();
    }

    public function storeProposta(array $data)
    {
        try
        {
            return DB::transaction(function() use($data){
                $proposta = $this->proposta->create($data);
                return $proposta;
            });
            
        }
        catch(\Exception $e)
        {
            throw new DatabaseInsertException("Erro ao criar proposta.", StatusCode::SERVER_ERROR, $e);
        }
    }
}


?>