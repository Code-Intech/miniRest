<?php

namespace MiniRest\DTO;

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
    public string $tb_end_idtb_end;


    public function __construct(
        protected Request $request,
    )
    {
        $this->nomeCompleto         = $request->json('nomeCompleto');
        $this->dataNascimento       = $request->json('dataNascimento');
        $this->genero               = $request->json('genero');
        $this->telefone             = $request->json('telefone');
        $this->email                = $request->json('email');
        $this->senha                = $request->json('senha');
        $this->cpf                  = $request->json('cpf');
        $this->tb_end_idtb_end      = "";
    }

    public function setAddress(int $addressId)
    {
        $this->tb_end_idtb_end = $addressId;
    }

    public function toArray(): array
    {
        return [
            'Nome_completo' => $this->nomeCompleto,
            'Data_Nacimento' => $this->dataNascimento,
            'idgenero' => $this->genero,
            'Telefone' => $this->telefone,
            'Email' => $this->email,
            'Senha' => $this->senha,
            'CPF' => $this->cpf,
            'tb_end_idtb_end' => $this->tb_end_idtb_end
        ];
    }

}