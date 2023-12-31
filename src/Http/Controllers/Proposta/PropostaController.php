<?php

namespace MiniRest\Http\Controllers\Proposta;

use MiniRest\Exceptions\GetException;
use MiniRest\Exceptions\PropostaAceitaNotFoundException;
use MiniRest\Exceptions\PropostaNotFoundException;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Repositories\ContratanteRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Repositories\Proposta\PropostaRepository;
use MiniRest\Actions\Proposta\PropostaCreateAction;
use MiniRest\DTO\Proposta\PropostaCreateDTO;
use MiniRest\Http\Auth\Auth;
use MiniRest\Exceptions\DatabaseInsertException;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Helpers\StatusCode\StatusCode;

class PropostaController extends Controller
{
    private ContratanteRepository $contratante;
    private PrestadorRepository $prestador;
    private PropostaRepository $proposta;

    public function __construct()
    {
        $this->contratante = new ContratanteRepository();
        $this->prestador = new PrestadorRepository();
        $this->proposta = new PropostaRepository();
    }

    public function getAll(int $servicoId)
    {
        return Response::json(['proposta' => $this->proposta->getServicoProposta($servicoId)]);
    }

    public function getById(int $servicoId)
    {
        try {
            Response::json(['proposta' => $this->proposta->getServicoPropostaAceitaByid($servicoId)]);
        } catch (PropostaAceitaNotFoundException $exception) {
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }

    public function me(Request $request)
    {
        $userId = Auth::id($request);
        try {
            Response::json(['proposta' => $this->proposta->me($userId)]);
        } catch (GetException|PropostaNotFoundException $e) {
            Response::json(['error' => ['message' => $e->getMessage()]], $e->getCode());
        }
    }


    public function create(Request $request, $servicoId)
    {
        $validation = $request->rules(
            [
                "Valor_Proposta" => 'required',
                "Comentario" => 'required|string',
                "Data_Proposta" => 'required'
            ]
        )->validate();

        if(!$validation)
        {
            $request->errors();
            return;
        }

        $userId = Auth::id($request);
        $contratanteId = $this->contratante->getContratanteByServicoId($servicoId);
        $contratanteUserId = $this->contratante->getContratanteIdByUserId($userId);
        $contratanteUser = $this->contratante->getUserByServicoId($servicoId);
        $prestadorId = $this->prestador->getPrestadorByUserId($userId);
        
        if($prestadorId == null)
        {
            return Response::json(['message' => 'Cadastre-se como prestador para poder criar uma proposta'], StatusCode::REQUEST_ERROR);
        }
        else{
            $verificaProposta = $this->proposta->getPrestadorProposta($servicoId, $prestadorId);
        }

        if($contratanteUserId == null){
            if($verificaProposta->isEmpty())
                {
                    try
                    {
                        $proposta_action = new PropostaCreateAction();
                        $propostaId = $proposta_action->execute(new PropostaCreateDTO($request, $userId, $contratanteId, $prestadorId, $servicoId, $contratanteUser));

                        return Response::json(['message' => 'Proposta inserida com sucesso!', 'id_proposta' => $propostaId]);
                    }
                    catch(DatabaseInsertException $exception)
                    {
                        DB::rollback();
                        return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
                    }

                }
                else{
                    return Response::json(['message' => 'Você já inseriu uma proposta neste serviço.'], StatusCode::REQUEST_ERROR);
                }

        }
        else{
            $verificaContratante = $this->proposta->getContratanteProposta($contratanteUserId, $servicoId);

            if($verificaContratante->isEmpty())
            {
                if($verificaProposta->isEmpty())
                {
                    try
                    {
                        $proposta_action = new PropostaCreateAction();
                        $propostaId = $proposta_action->execute(new PropostaCreateDTO($request, $userId, $contratanteId, $prestadorId, $servicoId, $contratanteUser));

                        return Response::json(['message' => 'Proposta inserida com sucesso!', 'id_proposta' => $propostaId]);
                    }
                    catch(DatabaseInsertException $exception)
                    {
                        DB::rollback();
                        return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
                    }

                }
                else{
                    return Response::json(['message' => 'Você já inseriu uma proposta neste serviço.'], StatusCode::REQUEST_ERROR);
                }
            }
            else
            {
                return Response::json(['message' => 'Você não pode cadastrar uma proposta em seu próprio serviço!'], StatusCode::REQUEST_ERROR);
            }
        }   
    }

    public function accept(int $propostaId)
    {

        $servicoId = $this->proposta->getServicoIdByPropostaId($propostaId);
        $proposta_aceita = $this->proposta->getPropostaAceita($servicoId);

        if($proposta_aceita->isEmpty())
        {
            try
            {
                $this->proposta->acceptProposta($propostaId);
                return Response::json(['message' => 'Proposta aceita!']);
            }
            catch(DatabaseInsertException $exception)
            {
                DB::rollback();
                return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
            }
        }
        else{
            return Response::json(['message' => 'Este serviço já tem uma proposta aceita'], StatusCode::REQUEST_ERROR);
        }
        
    }

    public function delete(int $propostaId)
    {
        try
        {
            $this->proposta->deleteProposta($propostaId);
            return Response::json(['message' => 'Proposta deletada com sucesso!']);
        }
        catch(DatabaseInsertException $exception)
        {
            DB::rollback();
            return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }
    
}

?>