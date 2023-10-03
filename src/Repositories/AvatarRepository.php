<?php

namespace MiniRest\Repositories;

use MiniRest\Models\Photos;

class AvatarRepository
{
    private Photos $photos;

    public function __construct()
    {
        $this->photos = new Photos();
    }

    public function storeAvatar(int $id, string $fileName): void
    {
        $this->photos
            ->updateOrCreate(
                ['tb_user_idtb_user' => $id],
                ['Foto_De_Perfil' => $fileName],
            );
    }

    public function getUserAvatar(int $id)
    {
        return $this->photos->select('Foto_De_Perfil')->where('tb_user_idtb_user', '=', $id)->value('Foto_De_Perfil');
    }
}