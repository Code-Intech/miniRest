<?php

namespace MiniRest\DTO\User;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class AvatarUploadDTO implements DTO
{

    public $file;


    public function __construct(
        protected Request $request,
    )
    {
        $this->file         = $request->files('avatar');
    }

    public function toArray(): array
    {
        return [
            'Foto_De_perfil' => $this->file,
        ];
    }
}