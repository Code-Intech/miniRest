<?php

namespace MiniRest\DTO\Services;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class ServiceCreateDTO implements DTO
{
    public string $id;
    public string $dataInicio;
    public string $estimativaDeDistancia;
    public string $estimativaValor;
    public string $estimativaIdade;
    public string $remotoPresencial;
    public string $estimativaDeTermino;
    public string $descricao;
    public string $dataCadastroServico;
    public string $tb_contratante_idtb_contratante;
    public string $tb_contratante_tb_user_idtb_user;
    public string $tb_end_idtb_end;

    public function __construct(protected Request $request,)
    {
        $this->dataInicio                       = $request->json('dataInicio');
        $this->estimativaDeDistancia            = $request->json('estimativaDeDistancia');
        $this->estimativaValor                  = $request->json('estimativaValor');
        $this->estimativaIdade                  = $request->json('estimativaIdade');
        $this->remotoPresencial                 = $request->json('remotoPresencial');
        $this->estimativaDeTermino              = $request->json('estimativaDeTermino');
        $this->descricao                        = $request->json('descricao');
        $this->dataCadastroServico              = $request->json('dataCadastroServico');
        $this->tb_contratante_idtb_contratante  = "";
        $this->tb_contratante_tb_user_idtb_user = "";
        $this->tb_end_idtb_end                  = "";
    }

    public function setAddress(int $addressId)
    {
        $this->tb_end_idtb_end = $addressId;
    }

    public function setContractor(int $contractorId)
    {
        $this->tb_contratante_idtb_contratante = $contractorId;
    }

    public function setUser(int $userId)
    {
        $this->tb_contratante_tb_user_idtb_user = $userId;
    }

    
    public function toArray(): array
    {
        return [
            'Data_Inicio' => $this->dataInicio,
            'Estimativa_de_distancia' => $this->estimativaDeDistancia,
            'Estimativa_Valor' => $this->estimativaValor,
            'Estimativa_Idade' => $this->estimativaIdade,
            'Remoto_Presencial' => $this->remotoPresencial,
            'Estimativa_de_Termino' => $this->estimativaDeTermino,
            'Desc' => $this->descricao,
            'Data_Cadastro_Servico' => $this->dataCadastroServico,
            'tb_contratante_idtb_contratante' => $this->tb_contratante_idtb_contratante,
            'tb_contratante_tb_user_idtb_user' => $this->tb_contratante_tb_user_idtb_user,
            'tb_end_idtb_end' => $this->tb_end_idtb_end
        ];
    }
}

?>