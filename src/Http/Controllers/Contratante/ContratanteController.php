<?php

namespace MiniRest\Http\Controllers\Contratante;

use MiniRest\Actions\Contratante\ContratanteCreateAction;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Repositories\ContratanteRepository;

class ContratanteController extends Controller
{
    private ContratanteRepository $contratanteRepository;

    public function __construct()
    {
        $this->contratanteRepository = new ContratanteRepository();
    }

    public function store(Request $request)
    {
        $userId = Auth::id($request);

        $contratanteCreateAction = new ContratanteCreateAction($this->contratanteRepository);
        
        try {
            $contratanteId = $contratanteCreateAction->execute($userId);
            Response::json(['contratante_id' => $contratanteId]);
        } catch (DatabaseInsertException $exception) {
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }
}
