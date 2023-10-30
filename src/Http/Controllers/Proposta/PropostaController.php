<?php

namespace MiniRest\Http\Controllers\Proposta;

use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Repositories\ContratanteRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Actions\Proposta\PropostaCreateAction;
use MiniRest\DTO\Proposta\PropostaCreateDTO;
use MiniRest\Http\Auth\Auth;
use MiniRest\Exceptions\DatabaseInsertException;
use Illuminate\Database\Capsule\Manager as DB;

class PropostaController extends Controller
{
    private ContratanteRepository $contratante;
    private PrestadorRepository $prestador;

    public function __construct()
    {
        $this->contratante = new ContratanteRepository();
        $this->prestador = new PrestadorRepository();
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
        $contratanteUser = $this->contratante->getUserByServicoId($servicoId);
        $prestadorId = $this->prestador->getPrestadorByUserId($userId);

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
}

?>