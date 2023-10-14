<?php

namespace MiniRest\Http\Controllers\Prestador;

use MiniRest\Actions\Prestador\PrestadorCreateAction;
use MiniRest\DTO\Prestador\PrestadorCreateDTO;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;

class PrestadorController extends Controller
{
    public function store(Request $request)
    {
        $validation = $request->rules([
            'valor_diaria' => 'required',
            'valor_hora' => 'required',
            'cnpj' => 'required|CNPJ',
            'nome_empresa' => 'required',
            'habilidades' => 'required|array:int',
            'profissoes' => 'required|array',
            'apresentacao' => 'required|string',
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        (new PrestadorCreateAction())->execute(
            Auth::id($request),
            new PrestadorCreateDTO($request)
        );

    }
}