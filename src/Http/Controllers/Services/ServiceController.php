<?php

namespace MiniRest\Http\Controllers\Services;

use MiniRest\Actions\Services\ServiceCreateAction;
use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\Services\ServiceCreateDTO;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Models\Service;
use MiniRest\Repositories\ServiceRepository;

class ServiceController extends Controller
{
    public function index()
    {
        Response::json(['service' => $this->paginate(Service::query())]);
    }

    public function me(Request $request)
    {
        $serviceId = Auth::id($request);
        Response::json(['service' => (new ServiceRepository)->me($serviceId)]);
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $validation = $request->rules([
            'dataInicio' => 'required|string',
            'estimativaDeDistancia' => 'required|string',
            'estimativaValor' => 'required|string',
            'estimativaIdade' => 'required|string',
            'remotoPresencial' => 'required|string',
            'estimativaDeTermino' => 'required|string',
            'descricao' => 'required|string',
            'dataCadastroServico' => 'required|string'
        ])->validate();

        if(!$validation){
            $request->errors();
            return;
        }

        $addressDTO = new AddressCreateDTO($request);
        $serviceDTO = new ServiceCreateDTO($request);

        (new ServiceCreateAction())->execute($serviceDTO, $addressDTO);

        Response::json([
            'message'=>'Serviço criado com sucesso!',
        ], StatusCode::CREATED);

    }

    // public function update(Request $request)
    // {
    //     $serviceId = Auth::id($request);
    //     $userAddressid = Auth::user($request)->id($request);
    //     $validation = $request->rules([
    //         'dataInicio' => 'required|string',
    //         'estimativaDeDistancia' => 'required|float',
    //         'estimativaValor' => 'required|float',
    //         'estimativaIdade' => 'required|float',
    //         'remotoPresencial' => 'required|int',
    //         'estimativaDeTermino' => 'required|string',
    //         'descricao' => 'required|string',
    //         'dataCadastroServico' => 'required|string'
    //     ])->validate();

    //     if (!$validation) {
    //         $request->errors();
    //         return;
    //     }

    //     $addressDTO = new AddressCreateDTO($request);
    //     $serviceDTO = new ServiceCreateDTO($request);

    //     (new ServiceUpdateAction())->execute($userId, $userAddressId, $userDTO, $addressDTO);

    //     Response::json([
    //         'message'=>'Usuário atualizado com sucesso',
    //     ]);
    // }
}
?>