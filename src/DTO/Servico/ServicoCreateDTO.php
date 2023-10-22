<?php

namespace MiniRest\DTO\Servico;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class ServicoCreateDTO implements DTO
{
    private Request $request;
    private $Titulo_Servico;
    private $Data_Inicio;
    private $Estimativa_de_distancia;
    private $Estimativa_Valor;
    private $Estimativa_Idade;
    private $Remoto_Presencial;
    private $Estimativa_de_Termino;
    private $Desc;
    private $tb_contratante_idtb_contratante;
    private $tb_contratante_tb_user_idtb_user;
    private $tb_end_idtb_end;
    private $profissoes;
    private $habilidades;

    public function __construct(Request $request, $tb_contratante_idtb_contratante, $tb_contratante_tb_user_idtb_user, $enderecoId, $profissoes, $habilidades)
    {
        $this->request = $request;
        $this->Titulo_Servico = $request->json('Titulo_Servico');
        $this->Data_Inicio = $request->json('Data_Inicio');
        $this->Estimativa_de_distancia = $request->json('Estimativa_de_distancia');
        $this->Estimativa_Valor = $request->json('Estimativa_Valor');
        $this->Estimativa_Idade = $request->json('Estimativa_Idade');
        $this->Remoto_Presencial = $request->json('Remoto_Presencial');
        $this->Estimativa_de_Termino = $request->json('Estimativa_de_Termino');
        $this->Desc = $request->json('Desc');
        $this->tb_contratante_idtb_contratante = $tb_contratante_idtb_contratante;
        $this->tb_contratante_tb_user_idtb_user = $tb_contratante_tb_user_idtb_user;
        $this->tb_end_idtb_end = $enderecoId;
        $this->profissoes = $profissoes;
        $this->habilidades = $habilidades;
        
    }

    function toArray(): array
    {
        return [
            'Titulo_Servico' => $this->Titulo_Servico,
            'Data_Inicio' => $this->Data_Inicio,
            'Estimativa_de_distancia' => $this->Estimativa_de_distancia,
            'Estimativa_Valor' => $this->Estimativa_Valor,
            'Estimativa_Idade' => $this->Estimativa_Idade,
            'Remoto_Presencial' => $this->Remoto_Presencial,
            'Estimativa_de_Termino' => $this->Estimativa_de_Termino,
            'Desc' => $this->Desc,
            'tb_contratante_idtb_contratante' => $this->tb_contratante_idtb_contratante,
            'tb_contratante_tb_user_idtb_user' => $this->tb_contratante_tb_user_idtb_user,
            'tb_end_idtb_end' => $this->tb_end_idtb_end,
            'profissoes' => $this->profissoes,
            'habilidades'=> $this->habilidades,
        ];
    }
}

?>