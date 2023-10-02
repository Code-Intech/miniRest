<?php

namespace MiniRest\DTO\User;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class UserFlgStatusDTO implements DTO
{
    public string $id;
    public int $flg;

    public function __construct(
        protected Request $request
    )
    {
        $this->flg         = $request->json('flg');
    }

    public function toArray(): array
    {
        return [
            'FlgStatus' => $this->flg,
        ];
    }
}