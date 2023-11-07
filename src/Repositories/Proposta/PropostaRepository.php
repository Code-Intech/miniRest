<?php

namespace MiniRest\Repositories\Proposta;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\PropostaAceitaNotFoundException;
use MiniRest\Models\Proposta\Proposta;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Exceptions\PropostaNotFoundException;
use MiniRest\Exceptions\GetException;
use MiniRest\Helpers\StatusCode\StatusCode;
use Illuminate\Database\Capsule\Manager as DB;


class PropostaRepository
{
    private $proposta;

    public function __construct()
    {
        $this->proposta = new Proposta();
    }

    public function getServicoIdByPropostaId(int $propostaId)
    {
        $servicoId = Proposta::where('idtb_proposta', $propostaId)
            ->value('tb_servico_idtb_servico');

            return $servicoId;
    }

    public function getPropostaAceita($servicoId)
    {
        $proposta = Proposta::where('Proposta_Aceita', 1)
            ->where('tb_servico_idtb_servico', $servicoId)
            ->get();
            return $proposta;
    }

    public function getPrestadorProposta(int $servicoId, int $prestadorId)
    {
        $proposta = Proposta::where('tb_prestador_idtb_prestador', $prestadorId)
            ->where('tb_servico_idtb_servico', $servicoId)
            ->get();

            return $proposta;
    }

    public function getContratanteProposta(int $contratanteId, $servicoId)
    {
        $proposta = Proposta::where('tb_servico_tb_contratante_idtb_contratante', $contratanteId)
            ->where('tb_servico_idtb_servico', $servicoId)
            ->get();

            return $proposta;
    }

    public function getServicoProposta(int $servicoId)
    {
        try{
            $propostas = Proposta::select('idtb_proposta', 'Proposta_Aceita', 'Valor_Proposta', 'Comentario', 'Data_Proposta', 'tb_servico_idtb_servico', 'tb_servico_tb_contratante_idtb_contratante', 'tb_servico_tb_contratante_tb_user_idtb_user', 'tb_prestador_idtb_prestador', 'tb_prestador_tb_user_idtb_user')
                ->where('tb_servico_idtb_servico', $servicoId)
                ->get();
            
                return $propostas;

        }catch(\Exception $e){
            throw new GetException("Não foi possível retornar os dados.", $e->getMessage());
        }
    }

    /**
     * @throws PropostaAceitaNotFoundException
     */
    public function getServicoPropostaAceitaByid(int $servicoId)
    {
        try{

            return Proposta::where('tb_servico_idtb_servico', $servicoId)
                ->where('Proposta_Aceita', 1)
                ->firstOrFail();

        }catch(ModelNotFoundException $e){
            throw new PropostaAceitaNotFoundException("Nenhuma proposta foi aceita até o momento");
        }
    }

    public function me(int $userId)
    {
        try{
            $propostas = Proposta::select('idtb_proposta', 'Proposta_Aceita', 'Valor_Proposta', 'Comentario', 'Data_Proposta', 'tb_servico_idtb_servico', 'tb_servico_tb_contratante_idtb_contratante', 'tb_servico_tb_contratante_tb_user_idtb_user', 'tb_prestador_idtb_prestador', 'tb_prestador_tb_user_idtb_user')
                ->where('tb_prestador_tb_user_idtb_user', $userId)
                ->get();
            
                return $propostas;

        }catch(\Exception $e){
            throw new GetException("Não foi possível retornar os dados.", $e->getMessage());
        }
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

    public function getPropostaId(int $servicoId)
    {
        try
        {
            return DB::transaction(function() use($servicoId){
                $proposta = $this->proposta->where('tb_servico_idtb_servico', $servicoId)
                    ->where('Proposta_Aceita', 1)
                    ->value('idtb_proposta');
                
                    return $proposta;
            });
            

        }
        catch(\Exception $e)
        {
            throw new PropostaNotFoundException("Proposta não encontrada.", StatusCode::SERVER_ERROR, $e);
        }
    }
}


?>