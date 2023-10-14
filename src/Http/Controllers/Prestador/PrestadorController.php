<?php

namespace MiniRest\Http\Controllers\Prestador;

use MiniRest\Actions\Prestador\PrestadorCreateAction;
use MiniRest\Actions\Prestador\PrestadorUpdateAction;
use MiniRest\DTO\Prestador\PrestadorCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class PrestadorController extends Controller
{
    public function store(Request $request)
    {
        $validation = $request->rules([
            'valor_diaria' => 'required',
            'valor_hora' => 'required',
            'cnpj' => 'required|cnpj',
            'nome_empresa' => 'required',
            'habilidades' => 'required|array:int',
            'profissoes' => 'required|array',
            'apresentacao' => 'required|string',
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }
        try {
            (new PrestadorCreateAction())->execute(
                Auth::id($request),
                new PrestadorCreateDTO($request)
            );
        } catch (DatabaseInsertException $exception) {
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }


    }

    public function update(Request $request)
    {
        $validation = $request->rules([
            'valor_diaria' => 'required',
            'valor_hora' => 'required',
            'cnpj' => 'required|cnpj',
            'nome_empresa' => 'required',
            'habilidades' => 'required|array:int',
            'profissoes' => 'required|array',
            'apresentacao' => 'required|string',
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        (new PrestadorUpdateAction())->execute(
            Auth::id($request),
            new PrestadorCreateDTO($request)
        );

    }
}