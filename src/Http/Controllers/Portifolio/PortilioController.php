<?php

namespace MiniRest\Http\Controllers\Portifolio;

use MiniRest\Actions\Portifolio\CreatePortifolioUploadAction;
use MiniRest\Actions\Portifolio\DeleteAlbumPhotoPortifolioUploadAction;
use MiniRest\Actions\Portifolio\DeleteAlbumPortifolioUploadAction;
use MiniRest\Actions\Portifolio\GetPortifolioUserAction;
use MiniRest\Actions\Portifolio\PortifolioGetByIdAction;
use MiniRest\Actions\Portifolio\PutPhotosOnPortifolioUploadAction;
use MiniRest\Actions\Portifolio\UpdatePortifolioUploadAction;
use MiniRest\DTO\Portifolio\PortifolioCreateDTO;
use MiniRest\DTO\Portifolio\PortifolioPutPhotoDTO;
use MiniRest\DTO\Portifolio\PortifolioUpdateDTO;
use MiniRest\Exceptions\AlbumPhotoNotFoundException;
use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Exceptions\PortifolioUpdateNotAllowedException;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Repositories\Portifolio\PortifolioRepository;

class PortilioController
{
    public function store(Request $request)
    {
        $validation = $request->rules([
            'portifolioCover' => 'required|file:jpg,jpeg,png,gif',
            'portifolioPhotos' => 'required|multipleFiles:jpg,jpeg,png,gif',
        ])->validate('files');

        if (!$validation) {
            $request->errors();
            return;
        }

        $validationPost = $request->rules([
            'title' => 'required',
            'description' => 'required'
        ])->validate('post');

        if (!$validationPost) {
            $request->errors();
            return;
        }

        try {
            (new CreatePortifolioUploadAction())->execute(
                new PortifolioCreateDTO($request),
                Auth::id($request)
            );
        } catch (UploadErrorException|PrestadorNotFoundException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json(['sucess' => 'portifólio criado']);
    }

    public function putPhoto(Request $request, $portifolioId)
    {
        $validation = $request->rules([
            'photo' => 'required|file:jpg,jpeg,png,gif',
        ])->validate('files');

        if (!$validation) {
            $request->errors();
            return;
        }

        try {
            (new PutPhotosOnPortifolioUploadAction())->execute(
                new PortifolioPutPhotoDTO($request),
                Auth::id($request),
                $portifolioId
            );
        } catch (UploadErrorException|PrestadorNotFoundException|PortifolioPrestadorNotFoundException|PortifolioUpdateNotAllowedException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json(['sucess' => 'foto adiciona ao portifólio com sucesso']);
    }

    public function update(Request $request, $portifolioId)
    {

        $validation = $request->rules([
            'portifolioCover' => 'required|file:jpg,jpeg,png,gif',
        ])->validate('files');

        if (!$validation) {
            $request->errors();
            return;
        }

        $validationPost = $request->rules([
            'title' => 'required',
            'description' => 'required'
        ])->validate('post');

        if (!$validationPost) {
            $request->errors();
            return;
        }

        try {
            (new UpdatePortifolioUploadAction())->execute(
                new PortifolioUpdateDTO($request),
                Auth::id($request),
                $portifolioId
            );
        } catch (UploadErrorException|PrestadorNotFoundException|PortifolioPrestadorNotFoundException|PortifolioUpdateNotAllowedException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json(['sucess' => 'portifólio atualizado']);
    }

    public function getUserPortifolios(Request $request)
    {

        try {
            $data = (new GetPortifolioUserAction())->execute(
                new PortifolioRepository(),
                Auth::id($request),
                'https://s3connectfreela.s3.sa-east-1.amazonaws.com/portifolio'
            );

        } catch (\Exception $exception) {
            Response::json(['error'=> $exception->getMessage()], StatusCode::NOT_FOUND);
            return;
        }

        Response::json(['portifolios' => $data]);
    }

    public function getPortifoliosByUserId($id)
    {

        try {
            $data = (new GetPortifolioUserAction())->execute(
                new PortifolioRepository(),
                $id,
                'https://s3connectfreela.s3.sa-east-1.amazonaws.com/portifolio'
            );

        } catch (PortifolioPrestadorNotFoundException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json(['portifolios' => $data]);
    }

    public function getPortifolioAlbumById($id)
    {

        try {
            $data = (new PortifolioGetByIdAction())->execute(
                new PortifolioRepository(),
                $id,
                'https://s3connectfreela.s3.sa-east-1.amazonaws.com/portifolio'
            );

        } catch (PortifolioPrestadorNotFoundException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json($data);
    }

    public function deleteAlbumPhotoById(Request $request, $photoId)
    {
        try {
            (new DeleteAlbumPhotoPortifolioUploadAction())->execute(
                new PortifolioRepository(),
                $photoId,
                Auth::id($request)
            );

        } catch (PrestadorNotFoundException|AlbumPhotoNotFoundException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json(['sucess' => 'Foto deletada com sucesso']);

    }

    public function deleteAlbumById(Request $request, $portifolioId)
    {
        try {
            (new DeleteAlbumPortifolioUploadAction())->execute(
                new PortifolioRepository(),
                $portifolioId,
                Auth::id($request)
            );

        } catch (PrestadorNotFoundException|PortifolioPrestadorNotFoundException $exception) {
            Response::json(['error'=> $exception->getMessage()], $exception->getCode());
            return;
        }

        Response::json(['sucess' => 'Portifólio deletado com sucesso']);

    }
}