<?php

namespace MiniRest\Http\Controllers\Servico;

use MiniRest\Actions\Servico\ServicoCreateAction;
use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class ServicoController extends Controller
{
    public function store(Request $request)
    {
        $validation = $request->rules([
            'Data_Inicio' => 'required',
            'Estimativa_de_distancia' => 'required',
            'Estimativa_Valor' => 'required',
            'Estimativa_Idade' => 'required',
            'Remoto_Presencial' => 'required',
            'Estimativa_de_Termino' => 'required',
            'Desc' => 'required',
        ])->validate();
        
        if (!$validation) {
            $request->errors();
            return;
        }

        try {
            (new ServicoCreateAction())->execute(new ServicoCreateDTO($request));
            return Response::json(['message' => 'Serviço criado com sucesso'], 201);
        } catch (DatabaseInsertException $exception) {
            return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }
}
