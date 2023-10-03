<?php

namespace MiniRest\Http\Controllers\Avatar;

use MiniRest\Actions\User\UserGetAvatarAction;
use MiniRest\Actions\User\UserUploadAvatarAction;
use MiniRest\Exceptions\AvatarNotFoundException;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class AvatarController
{

    public function uploadAvatar(Request $request)
    {
        $userId = Auth::id($request);

        $validation = $request->rules([
            'avatar' => 'required|file:png,jpg,jpeg',
        ])->validate('files');;

        if (!$validation) {
            $request->errors();
            return;
        }

        try {
            $avatar = (new UserUploadAvatarAction())->execute($request, $userId);
        } catch (UploadErrorException $e) {
            Response::json(['error' => 'Error ao fazer o upload do arquivo'], $e->getCode());
            return;
        }
        Response::json(['success' => ['message' => 'Upload efetuado com sucesso', 'avatar_url' => $avatar]]);
    }

    public function avatar(Request $request)
    {
        $userId = Auth::id($request);

        try {
            $avatar = (new UserGetAvatarAction())->execute($userId);
        } catch (AvatarNotFoundException $e) {
            Response::json(['error' => $e->getMessage()], $e->getCode());
            return;
        }

        Response::json(['success' => ['avatar_url' => $avatar]]);
    }
}