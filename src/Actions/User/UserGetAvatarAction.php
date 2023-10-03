<?php

namespace MiniRest\Actions\User;

use MiniRest\Exceptions\AvatarNotFoundException;
use MiniRest\Repositories\AvatarRepository;
use MiniRest\Storage\S3Storage;
use PDOException;

class UserGetAvatarAction
{
    /**
     * @throws AvatarNotFoundException
     */
    public function execute(int $userId): string
    {
        $storage = new S3Storage();

        $name = (new AvatarRepository())->getUserAvatar($userId);

        if (strlen($name) < 1) {
            throw new AvatarNotFoundException();
        }

        return ($storage->generatePublicdUrl("avatar/" . $name));
    }
}