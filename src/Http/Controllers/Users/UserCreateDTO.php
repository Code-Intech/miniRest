<?php

namespace MiniRest\Http\Controllers\Users;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class UserCreateDTO implements DTO
{
    public string $id;
    public string $nomeCompleto;
    public string $dataNascimento;
    public string $genero;
    public string $telefone;
    public string $email;
    public string $senha;
    public string $cpf;
    public string $cep;
    public string $rua;
    public string $regiao;
    public string $bairro;

    public function __construct(
        protected Request $request
    )
    {
        $this->nomeCompleto         = $request->json('nomeCompleto');
        $this->dataNascimento       = $request->json('dataNascimento');
        $this->genero               = $request->json('genero');
        $this->telefone             = $request->json('telefone');
        $this->email                = $request->json('email');
        $this->senha                = $request->json('senha');
        $this->cpf                  = $request->json('cpf');
        $this->cep                  = $request->json('cep');
        $this->rua                  = $request->json('rua');
        $this->regiao               = $request->json('regiao');
        $this->bairro               = $request->json('bairro');
    }

    public function toArray(): array
    {
        return [
            'Nome_completo' => $this->nomeCompleto,
            'Data_Nacimento' => $this->dataNascimento,
            'Genero' => $this->genero,
            'Telefone' => $this->telefone,
            'Email' => $this->email,
            'Senha' => $this->senha,
            'CPF' => $this->cpf,
            'CEP' => $this->cep,
            'Rua' => $this->rua,
            'Regiao' => $this->regiao,
            'Bairro' => $this->bairro
        ];
    }

}