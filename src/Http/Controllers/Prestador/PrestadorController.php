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
use MiniRest\Repositories\Prestador\PrestadorRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Helpers\StatusCode\StatusCode;

class PrestadorController extends Controller
{
    private PrestadorRepository $prestador;

    public function __construct()
    {
        $this->prestador = new PrestadorRepository();
    }

    public function index()
    {   
        Response::json(['prestador' => $this->prestador->getAll()]);
    }

    public function findById(int $prestadorId)
    {
        Response::json(['prestador' => $this->prestador->find($prestadorId)]);
    }

    public function me(Request $request)
    {
        try {
            Response::json(['prestador' => $this->prestador->me(Auth::id($request))]);
        } catch (ModelNotFoundException $exception) {
            Response::json(['error' => 'Usuário não cadastrado como prestador', $exception->getMessage()], StatusCode::SERVER_ERROR);
        }
    }

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
            $prestadorId = (new PrestadorCreateAction())->execute(
                Auth::id($request),
                new PrestadorCreateDTO($request)
            );
            Response::json(['success' => ['message' => 'prestador cadastrado com sucesso','prestadorId' => $prestadorId]]);
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