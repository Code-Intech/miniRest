<?php

namespace MiniRest\Actions\User;

use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Http\Request\Request;
use MiniRest\Storage\S3Storage;

class UserUploadAvatarAction
{
    /**
     * @throws UploadErrorException
     */
    public function execute(Request $request): void
    {
        $file = $request->files('avatar');
        $storage = new S3Storage();
        $storage->upload("teste/" . $file['name'], $file['tmp_name']);
    }

}