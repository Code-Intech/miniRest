<?php

namespace MiniRest\Http\Controllers\Servico;

use MiniRest\Actions\Servico\ServicoUploadImgAction;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Repositories\ContratanteRepository;

class ServicoImgController
{
    public function uploadImage(Request $request, int $servicoId)
    {
        $userId = Auth::id($request);
        $contratanteRepository = new ContratanteRepository();
        $contratanteId = $contratanteRepository->getContratanteIdByUserId($userId);
 
        var_dump($servicoId);
        
        $validation = $request->rules([
            'image' => 'required|file:jpeg,png,jpg',
        ])->validate('files');

        if (!$validation) {
            $request->errors();
            return;
        }

        try {
            $image = (new ServicoUploadImgAction())->execute($request->files('image'), $servicoId, $contratanteId, $userId);
        } catch (UploadErrorException $e) {
            Response::json(['error' => 'Erro ao fazer o upload da imagem'], $e->getCode());
            return;
        }

        Response::json(['success' => ['message' => 'Upload efetuado com sucesso', 'image_url' => $image]]);
    }
}
