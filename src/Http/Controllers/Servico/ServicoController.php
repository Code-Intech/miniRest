<?php

namespace MiniRest\Http\Controllers\Servico;

use MiniRest\Actions\Servico\ServicoCreateAction;
use MiniRest\Actions\Servico\ServicoDeleteImageByIdAction;
use MiniRest\Actions\Servico\ServicoUpdateAction;
use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\Servico\ServicoCreateDTO;
use MiniRest\Exceptions\AccessNotAllowedException;
use MiniRest\Exceptions\DatabaseInsertException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\ImagesNotFoundException;
use MiniRest\Exceptions\InvalidJsonResponseException;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Auth\Auth;
use MiniRest\Repositories\AddressRepository;
use MiniRest\Repositories\ContratanteRepository;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Actions\Servico\ServicoUploadImageAction;
use MiniRest\DTO\Servico\ServicoUpdateDTO;
use MiniRest\DTO\Servico\ServicoUploadImageDTO;
use MiniRest\Repositories\Servico\ServicoRepository;
use MiniRest\Actions\Servico\ServicoUpdateProfissaoAction;
use MiniRest\DTO\Servico\ServicoUpdateProfissaoDTO;
use MiniRest\Actions\Servico\ServicoUpdateHabilidadeAction;
use MiniRest\DTO\Servico\ServicoUpdateHabilidadeDTO;
use MiniRest\Repositories\Proposta\PropostaRepository;
use MiniRest\Actions\Servico\ServicoFinalizadoAction;
use MiniRest\DTO\Servico\ServicoFinalizadoDTO;

class ServicoController extends Controller
{
    private ServicoRepository $servico;
    private ContratanteRepository $contratante;
    private AddressRepository $addressRepository;
    private PropostaRepository $proposta;

    public function __construct()
    {
        $this->servico = new ServicoRepository();
        $this->contratante = new ContratanteRepository();
        $this->addressRepository = new AddressRepository();
        $this->proposta = new PropostaRepository();
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

        $profissoes = $request->json('profissoes');
        $habilidades = $request->json('habilidades');

        try {
            $servicoCreateAction = new ServicoCreateAction();
            $servicoCreateAction->execute(new ServicoCreateDTO($request, $contratanteId, $userId, $enderecoId, $profissoes, $habilidades));

            Response::json(['message' => 'Serviço criado com sucesso'], 201);

        } catch (DatabaseInsertException $exception) {
            DB::rollback();
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }
    }

    public function update(Request $request, $servicoId)
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
        ])->validate();

        if(!$validation){
            $request->errors();
            return;
        }

        $userId = Auth::id($request);
        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);
        $enderecoDTO = new AddressCreateDTO($request);
        $enderecoId = $this->addressRepository->store($enderecoDTO->toArray());

        try{
            $servicoUpdateAction = new ServicoUpdateAction();
            $servicoUpdateAction->execute(new ServicoUpdateDTO($request, $servicoId, $contratanteId, $userId, $enderecoId));

            Response::json(['message' => 'Serviço atualizado com sucesso'], 201);

        }catch (DatabaseInsertException $exception) {
            DB::rollback();
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }


    }

    public function updateProfissao(Request $request, $servicoId)
    {
        $validation = $request->rules([
            'profissoes' => 'array'
        ])->validate();

        if(!$validation){
            $request->errors();
            return;
        }

        $userId = Auth::id($request);
        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);
        $profissoes = $request->json('profissoes');

        try{
            $servicoUpdateProfissaoAction = new ServicoUpdateProfissaoAction();
            $servicoUpdateProfissaoAction->execute(new ServicoUpdateProfissaoDTO($request, $servicoId, $contratanteId,$userId,$profissoes));

            Response::json(['message' => 'Profissões do Serviço atualizadas com sucesso'], 201);

        }catch(DatabaseInsertException $exception){
            DB::rollback();
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }


    }

    public function updateHabilidade(Request $request, $servicoId)
    {
        $validation = $request->rules(['habilidades' => 'array'])->validate();
        
        if(!$validation)
        {
            $request->errors();
            return;
        }

        $userId = Auth::id($request);
        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);
        $habilidades = $request->json('habilidades');
        

        try{
            $servicoUpdateHabilidadeAction = new ServicoUpdateHabilidadeAction();
            $servicoUpdateHabilidadeAction->execute(new ServicoUpdateHabilidadeDTO($request, $servicoId, $contratanteId, $userId, $habilidades));

            Response::json(['message' => 'Habilidades do Serviço atualizadas com sucesso'], 201);

        }catch(DatabaseInsertException $exception){
            DB::rollback();
            Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
        }

    }

    public function deleteServico(Request $request, $servicoId)
    {
        $userId = Auth::id($request);
        $servicoUser = $this->servico->getServicoUser($servicoId);
        
        if($servicoUser != $userId)
        {
            Response::json(['message' => 'Você não pode deletar o serviço de outro usuário']);
        }
        else
        {
            try
            {
                $this->servico->deleteServico($servicoId);
                Response::json(['message' => 'Serviço deletado com sucesso']);
            }
            catch(DatabaseInsertException $exception)
            {
                DB::rollback();
                Response::json(['error' => ['message' => $exception->getMessage()]], $exception->getCode());
            }
        }

        
    }
    
    public function uploadImage(Request $request, $servicoId)
    {
        $validation = $request->rules([
            'image' => 'required|multipleFiles:jpg,jpeg,png,gif',
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

            Response::json(['message' => 'Imagens inseridas com sucesso'], 201);
        }
        catch(DatabaseInsertException $exception){
            DB::rollback();
            Response::json(['error' => ['message'=> $exception->getMessage()]], $exception->getCode());
        }

    }

    // public function uploadAlbum(Request $request, $servicoId)
    // {
    //     $validation = $request->rules([
    //         'image' => 'required|multipleFiles:jpg,jpeg,png,gif',
    //     ])->validate('files');

    //     if(!$validation){
    //         $request->errors();
    //         return;
    //     }

    //     $userId = Auth::id($request);   
    //     $servicoId = $this->servico->getServicoId($servicoId);
    //     $contratanteId = $this->contratante->getContratanteIdByUserId($userId);


    //     try{
    //         $servicoUploadImageAction = new ServicoUploadImageAction();
    //         $servicoUploadImageAction->execute(new ServicoUploadImageDTO($request, $servicoId, $contratanteId, $userId));

    //         Response::json(['message' => 'Imagens inseridas com sucesso'], 201);
    //     }
    //     catch(DatabaseInsertException $exception){
    //         DB::rollback();
    //         Response::json(['error' => ['message'=> $exception->getMessage()]], $exception->getCode());
    //     }
    // }

    public function updateImages(Request $request, $servicoId)
    {
        $validation = $request->rules([
            'image' => 'required|multipleFiles:jpg,jpeg,png,gif',
        ])->validate('files');

        if(!$validation){
            $request->errors();
            return;
        }

        $userId = Auth::id($request);   
        $servicoId = $this->servico->getServicoId($servicoId);
        $contratanteId = $this->contratante->getContratanteIdByUserId($userId);


        try{
            $this->servico->deleteImages($servicoId);
            $servicoUploadImageAction = new ServicoUploadImageAction();
            $servicoUploadImageAction->execute(new ServicoUploadImageDTO($request, $servicoId, $contratanteId, $userId));

            Response::json(['message' => 'Imagens atualizadas com sucesso'], 201);
        }
        catch(DatabaseInsertException $exception){
            DB::rollback();
            Response::json(['error' => ['message'=> $exception->getMessage()]], $exception->getCode());
        }
    }

    public function deleteImages(Request $request, $servicoId)
    {
        $this->servico->deleteImages($servicoId);
        Response::json(['message' => 'Imagens deletadas com sucesso!']);
    }

    public function deleteImageByImageId(Request $request, int $id) {
        try {
            (new ServicoDeleteImageByIdAction())->execute(
                $id,
                Auth::id($request),
                new ServicoRepository()
            );

            Response::json(['message' => 'Imagem deletada com sucesso!']);
        } catch (AccessNotAllowedException|ImagesNotFoundException $e) {
            Response::json(['error' => ['message' => $e->getMessage()]], $e->getCode());
        }
    }

    public function getImages($servicoId)
    {
        try {
            Response::json($this->servico->getServicoImages($servicoId));
        } catch (ImagesNotFoundException $e) {
            Response::json(['error' => ['message' => $e->getMessage()]], $e->getCode());
        }
    }

    public function endServico(Request $request, $servicoId)
    {
        $data_finalizado = date("Y-m-d H:i:s");
        $propostaId = $this->proposta->getPropostaId($servicoId);
        
        try
        {
            $finalizaServicoAction = new ServicoFinalizadoAction();
            $finalizaServicoAction->execute(new ServicoFinalizadoDTO($request, $data_finalizado, $propostaId, $servicoId));

            Response::json(['message' => 'Serviço finalizado com sucesso!']);
        }
        catch(DatabaseInsertException $e)
        {
            DB::rollback();
            Response::json(['message' => 'Não foi possível finalizar o serviço', $e->getMessage()], $e->getCode());
        }
    }

}
