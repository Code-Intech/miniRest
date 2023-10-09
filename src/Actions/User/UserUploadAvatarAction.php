<?php

namespace MiniRest\Actions\User;

use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Http\Request\Request;
use MiniRest\Repositories\AvatarRepository;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;
use PDOException;

class UserUploadAvatarAction
{
    /**
     * @throws UploadErrorException
     */
    public function execute(Request $request, int $userId): string
    {
        $file = $request->files('avatar');
        $storage = new S3Storage(new PublicAcl());
        $name = UUIDFileName::uuidFileName($file['name']);

        try {
            (new AvatarRepository())->storeAvatar($userId, $name);
            $storage->upload("avatar/" . $name, $file['tmp_name']);
        } catch (PDOException $exception) {
            throw new UploadErrorException($exception->getMessage());
        }

        return ($storage->generatePublicdUrl("avatar/" . $name));
    }

}