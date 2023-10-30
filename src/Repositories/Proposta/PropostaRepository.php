<?php

namespace MiniRest\Repositories\Proposta;

use MiniRest\Models\Proposta\Proposta;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Exceptions\PropostaNotFoundException;
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

    public function acceptProposta(int $propostaId)
    {
        try
        {
            return DB::transaction(function() use($propostaId){
                $proposta = $this->proposta->where('idtb_proposta', $propostaId)->first();
                if($proposta)
                {
                    $proposta->update(['Proposta_Aceita' => 1]);
                }
            });
        }
        catch(\Exception $e)
        {
            throw new PropostaNotFoundException("Proposta não encontrada.", StatusCode::SERVER_ERROR, $e);
        }
    }

    public function deleteProposta(int $propostaId)
    {
        try
        {
            return DB::transaction(function() use($propostaId){
                $proposta = $this->proposta->where('idtb_proposta', $propostaId)->first();
                if($proposta)
                {
                    $proposta->delete();
                }
            });
        }
        catch(\Exception $e)
        {
            throw new PropostaNotFoundException("Proposta não encontrada.", StatusCode::SERVER_ERROR, $e);
        }
    }
}


?>