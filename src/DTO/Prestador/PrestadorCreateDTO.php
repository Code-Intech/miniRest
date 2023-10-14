<?php

namespace MiniRest\DTO\Prestador;

use MiniRest\DTO\DTO;
use MiniRest\Http\Request\Request;

class PrestadorCreateDTO implements DTO
{
    private Request $request;
    private float $valorDiaria;
    private float $valorHora;
    private string $cnpj;
    private string $nomeEmpresa;
    private array $habilidades;
    private array $profissoes;
    private string $apresentacao;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->valorDiaria = $this->request->json('valor_diaria');
        $this->valorHora = $this->request->json('valor_hora');
        $this->cnpj = $this->request->json('cnpj');
        $this->nomeEmpresa = $this->request->json('nome_empresa');
        $this->habilidades = $this->request->json('habilidades');
        $this->profissoes = $this->request->json('profissoes');
        $this->apresentacao = $this->request->json('apresentacao');
    }

    function toArray(): array
    {
        return [
            'Valor_Da_Hora' => $this->valorHora,
            'Valor_diaria' => $this->valorDiaria,
            'Nome_Empresa' => $this->nomeEmpresa,
            'CNPJ' => $this->cnpj,
            'tb_habilidades_idtb_habilidades' => $this->habilidades,
            'tb_prestador_profissao' => $this->profissoes,
            'Apresentacao' => $this->apresentacao
        ];
    }
}