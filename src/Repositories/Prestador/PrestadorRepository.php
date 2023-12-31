<?php

namespace MiniRest\Repositories\Prestador;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Prestador\Prestador;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Models\Prestador\PrestadorApresentacao;
use MiniRest\Models\Prestador\PrestadorHabilidades;
use MiniRest\Models\Prestador\PrestadorProfissao;

class PrestadorRepository
{
    private Prestador $prestador;

    public function __construct()
    {
        $this->prestador = new Prestador();
    }

    public function getAll()
    {
        $prestadores = Prestador::all();
        $data = [];

        foreach ($prestadores as $prestador) {
            $prestadorAll = Prestador::select('Nome_Empresa', 'Nome_completo','CNPJ', 'tb_user_idtb_user', 'idtb_prestador', 'Valor_Da_Hora', 'Valor_diaria')
                ->where('idtb_prestador', $prestador->idtb_prestador)
                ->join('tb_user', 'tb_prestador.tb_user_idtb_user', 'tb_user.idtb_user')
                ->first();

            $prestadorProfissao = PrestadorProfissao::select('Profissao', 'idtb_profissoes', 'Experiencia', 'Categoria', 'tb_categoria_idtb_categoria')
                ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_prestador_profissao.tb_profissoes_idtb_profissoes')
                ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
                ->where('tb_prestador_profissao.tb_prestador_idtb_prestador', $prestador->idtb_prestador)
                ->get();

            $prestadorHabilidades = PrestadorHabilidades::select('Habilidade', 'idtb_habilidades')
                ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_prestador_habilidade.tb_habilidades_idtb_habilidades')
                ->where('tb_prestador_habilidade.tb_prestador_idtb_prestador', $prestador->idtb_prestador)
                ->get();

            $prestadorApresentacao = PrestadorApresentacao::select('Apresentacao')
                ->where('tb_prestador_idtb_prestador', $prestador->idtb_prestador)
                ->first();

            $data[] = [
                'prestadorInfo' => $prestadorAll,
                'prestadorProfessions' => $prestadorProfissao,
                'prestadorSkills' => $prestadorHabilidades,
                'prestadorGrettings' => $prestadorApresentacao,
            ];
        }



        return $data;
    }

    /**
     * @throws PrestadorNotFoundException
     */
    public function find(int|string $prestadorId)
    {
        $data = [];

        $prestadorAll = Prestador::select('tb_user.*', 'tb_prestador.*')
            ->where('idtb_prestador', $prestadorId)
            ->join('tb_user', 'tb_prestador.tb_user_idtb_user', 'tb_user.idtb_user')
            ->first();

        $prestadorProfissao = PrestadorProfissao::select('Profissao', 'idtb_profissoes', 'Experiencia', 'Categoria', 'tb_categoria_idtb_categoria')
            ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_prestador_profissao.tb_profissoes_idtb_profissoes')
            ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
            ->where('tb_prestador_profissao.tb_prestador_idtb_prestador', $prestadorId)
            ->get();

        $prestadorHabilidades = PrestadorHabilidades::select('Habilidade', 'idtb_habilidades')
            ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_prestador_habilidade.tb_habilidades_idtb_habilidades')
            ->where('tb_prestador_habilidade.tb_prestador_idtb_prestador', $prestadorId)
            ->get();

        $prestadorApresentacao = PrestadorApresentacao::select('Apresentacao')
            ->where('tb_prestador_idtb_prestador', $prestadorId)
            ->first();

        if (
            !$prestadorAll ||
            count($prestadorProfissao) <= 0 ||
            count($prestadorHabilidades) <= 0 ||
            !$prestadorApresentacao
        ) throw new PrestadorNotFoundException();
        
        return [
            'prestadorInfo' => $prestadorAll,
            'prestadorProfessions' => $prestadorProfissao,
            'prestadorSkills' => $prestadorHabilidades,
            'prestadorGrettings' => $prestadorApresentacao,
        ];
    }

    public function me(int $userId)
    {
        $prestador = Prestador::where('tb_user_idtb_user', $userId)->firstOrFail();

        $prestadorAll = Prestador::select('Nome_Empresa', 'CNPJ', 'idtb_prestador', 'Valor_Da_Hora', 'Valor_diaria', 'Telefone', 'Email')
            ->join('tb_user', 'tb_prestador.tb_user_idtb_user', 'tb_user.idtb_user')
            ->where('idtb_prestador', $prestador->idtb_prestador)
            ->first();

        $prestadorProfissao = PrestadorProfissao::select('Profissao', 'idtb_profissoes', 'Experiencia', 'Categoria', 'tb_categoria_idtb_categoria')
            ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_prestador_profissao.tb_profissoes_idtb_profissoes')
            ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
            ->where('tb_prestador_profissao.tb_prestador_idtb_prestador', $prestador->idtb_prestador)
            ->get();

        $prestadorHabilidades = PrestadorHabilidades::select('Habilidade', 'idtb_habilidades')
            ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_prestador_habilidade.tb_habilidades_idtb_habilidades')
            ->where('tb_prestador_habilidade.tb_prestador_idtb_prestador', $prestador->idtb_prestador)
            ->get();

        $prestadorApresentacao = PrestadorApresentacao::select('Apresentacao')
            ->where('tb_prestador_idtb_prestador', $prestador->idtb_prestador)
            ->first();

        return [
            'prestadorInfo' => $prestadorAll,
            'prestadorProfessions' => $prestadorProfissao,
            'prestadorSkills' => $prestadorHabilidades,
            'apresentacao' => $prestadorApresentacao['Apresentacao'],
        ];
    }

    public function getPrestadorId(int $userId)
    {
        $prestador = Prestador::where('tb_user_idtb_user', $userId)->firstOrFail();
        return $prestador->idtb_prestador;

    }

    /**
     * @throws DatabaseInsertException
     */
    public function storePrestador(int $userId, array $data)
    {
        $id = $this->prestador
            ->firstOrCreate(
                ['tb_user_idtb_user' => $userId],
                [
                    'Valor_Da_Hora' => $data['Valor_Da_Hora'],
                    'Valor_diaria' => $data['Valor_diaria'],
                    'Nome_Empresa' => $data['Nome_Empresa'],
                    'CNPJ' => $data['cnpj'],
                    'tb_user_idtb_user' => $userId
                ]
            );

        if ($id->id === null)
            throw new DatabaseInsertException(
                'error ao fazer o insert, prestador já foi cadastrado.',
                StatusCode::SERVER_ERROR
            );

        return $id->id;


    }

    public function updatePrestador(int $userId, array $data)
    {
        return $this->prestador
            ->where('tb_user_idtb_user', $userId)
            ->update(
                [
                    'Valor_Da_Hora' => $data['Valor_Da_Hora'],
                    'Valor_diaria' => $data['Valor_diaria'],
                    'Nome_Empresa' => $data['Nome_Empresa'],
                    'CNPJ' => $data['cnpj'],
                    'tb_user_idtb_user' => $userId
                ]
            );
    }

    public function getPrestadorByUserId(int $userId)
    {
        $prestador = Prestador::where('tb_user_idtb_user', $userId)->first();
        if($prestador)
        {
            return $prestador->idtb_prestador;
        }
    }

}