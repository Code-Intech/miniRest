<?php

namespace MiniRest\Http\Controllers\Servico;

use MiniRest\Actions\Servico\ServicoCreateAction;
use MiniRest\Actions\Servico\ServicoUpdateAction;
use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\DatabaseInsertException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Auth\Auth;
use MiniRest\Repositories\AddressRepository;
use MiniRest\Repositories\ContratanteRepository;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Actions\Servico\ServicoUploadImageAction;
use MiniRest\DTO\Servico\ServicoUploadImageDTO;
use MiniRest\Repositories\Servico\ServicoRepository;

class ServicoController extends Controller
{
    private ServicoRepository $servico;
    private ContratanteRepository $contratante;
    private AddressRepository $addressRepository;

    public function __construct()
    {
        $this->servico = new ServicoRepository();
        $this->contratante = new ContratanteRepository();
        $this->addressRepository = new AddressRepository();
    }

    public function index()
    {
        Response::json(['servico' => $this->servico->getAll()]);
    }

    public function findById(int $servicoId)
    {
        Response::json(['servico' => $this->servico->find($servicoId)]);
    }

    public function me(Request $request)
    {
        try {
            Response::json(['servico' => $this->servico->me(Auth::id($request))]);
        } catch (ModelNotFoundException $exception) {
            
        }
    }

    public function store(Request $request)
    {
        $validation = $request->rules([
            'Titulo_Servico' => 'required',
            'Data_Inicio' => 'required',
            'Estimativa_de_distancia' => 'required',
            'Estimativa_Valor' => 'required',
            'Estimativa_Idade' => 'required',
            'Remoto_Presencial' => 'required',
            'Estimativa_de_Termino' => 'required',
            'Desc' => 'required',
            'profissoes' => 'array',
            'habilidades' => 'array',
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $userId = Auth::id($request);
        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);

        if(!$contratanteId)
        {
            $contratanteId = $this->contratante->storeContratante($userId);
        }

        $enderecoDTO = new AddressCreateDTO($request);
        $enderecoId = $this->addressRepository->store($enderecoDTO->toArray());

        $tb_contratante_idtb_contratante = $contratanteId;

        $profissoes = $request->json('profissoes');
        $habilidades = $request->json('habilidades');

        try {
            $servicoCreateAction = new ServicoCreateAction();
            $servicoCreateAction->execute(new ServicoCreateDTO($request, $tb_contratante_idtb_contratante, $userId, $enderecoId, $profissoes, $habilidades));

            return Response::json(['message' => 'Serviço criado com sucesso'], 201);

        } catch (DatabaseInsertException $exception) {
            DB::rollback();
            return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }

    public function uploadImage(Request $request, $servicoId)
    {
        $validation = $request->rules([
            'image' => 'required|file:jpg,jpeg,png,gif',
        ])->validate('files');

        if(!$validation){
            $request->errors();
            return;
        }

        $userId = Auth::id($request);   
        $servicoId = $this->servico->getServicoId($servicoId);
        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);


        try{
            $servicoUploadImageAction = new ServicoUploadImageAction();
            $servicoUploadImageAction->execute(new ServicoUploadImageDTO($request, $servicoId, $contratanteId, $userId));

            return Response::json(['message' => 'Imagens inseridas com sucesso'], 201);
        }
        catch(DatabaseInsertException $exception){
            DB::rollback();
            return Response::json(['error' => ['message'=> $exception->getMessage()]], $exception->getCode());
        }

    }

    public function update(Request $request, int $servicoId)
    {
        $validation = $request->rules([
            'Data_Inicio' => 'required',
            'Estimativa_de_distancia' => 'required',
            'Estimativa_Valor' => 'required',
            'Estimativa_Idade' => 'required',
            'Remoto_Presencial' => 'required',
            'Estimativa_de_Termino' => 'required',
            'Desc' => 'required',
            'profissoes' => 'array',
            'habilidades' => 'array',
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $userId = Auth::id($request);

        $enderecoRepository = new AddressRepository();
        $enderecoDTO = new AddressCreateDTO($request);
        $enderecoId = $enderecoRepository->store($enderecoDTO->toArray());



        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);

        if(!$contratanteId)
        {
            $contratanteId = $this->contratante->storeContratante($userId);
        }

        $tb_contratante_idtb_contratante = $contratanteId;

        $profissoes = $request->json('profissoes');
        $habilidades = $request->json('habilidades');

        try {
            $servicoUpdateAction = new ServicoUpdateAction();
            $servicoUpdateAction->execute($userId, new ServicoCreateDTO($request, $servicoId, $tb_contratante_idtb_contratante, $enderecoId, $profissoes, $habilidades));

            return Response::json(['message' => 'Serviço atualizado com sucesso']);
        } catch (DatabaseInsertException $exception) {
            DB::rollback();
            return Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }

}
