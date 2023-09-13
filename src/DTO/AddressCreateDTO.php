<?php

namespace MiniRest\DTO;

use MiniRest\Http\Request\Request;

class AddressCreateDTO implements DTO
{
    public string $cep;
    public string $rua;
    public string $bairro;
    public string $cidade;
    public string $estado;

    public function __construct(
        protected Request $request
    )
    {
        $this->cep                  = $request->json('cep');
        $this->rua                  = $request->json('rua');
        $this->bairro               = $request->json('bairro');
        $this->cidade               = $request->json('cidade');
        $this->estado               = $request->json('estado');
    }



    function toArray(): array
    {
        return [
            'CEP' => $this->cep,
            'Rua' => $this->rua,
            'Bairro' => $this->bairro,
            'Cidade' => $this->cidade,
            'Estado' => $this->estado,
        ];
    }
}