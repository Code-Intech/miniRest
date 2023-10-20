<?php

namespace MiniRest\Repositories\Prestador;

use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Models\Prestador\Prestador;
use Illuminate\Database\Capsule\Manager as DB;

class PrestadorRepository
{
    private Prestador $prestador;

    public function __construct()
    {
        $this->prestador = new Prestador();
    }

    public function getAll()
    {
        $results = DB::table('tb_prestador')
            ->select('tb_prestador.*')
            ->selectRaw('GROUP_CONCAT(tb_habilidades.idtb_habilidades) as habilidades_id')
            ->selectRaw('GROUP_CONCAT(tb_habilidades.Habilidade) as habilidades')
            ->join('tb_prestador_habilidade', 'tb_prestador.idtb_prestador', '=', 'tb_prestador_habilidade.tb_prestador_idtb_prestador')
            ->join('tb_habilidades', 'tb_prestador_habilidade.tb_habilidades_idtb_habilidades', '=', 'tb_habilidades.idtb_habilidades')
            ->groupBy('tb_prestador.idtb_prestador')
            ->get();
        
            $prestadoresWithSkills = $results->map(function($result){
                $habilidadeIds = explode(',', $result->habilidades_id);
                $habilidades = explode(',', $result->habilidades);
                $skills = [];

                foreach($habilidadeIds as $index => $habilidadeId){
                    $skills[] = [
                        'id' => $habilidadeId,
                        'habilidade' => $habilidades[$index],
                    ];
                }

                $result->skills = $skills;
                unset($result->habilidades_id);
                unset($result->habilidades);
                return $result;
            });

            return $prestadoresWithSkills;

    }

    public function find(int|string $prestadorId)
    {
        $results = DB::table('tb_prestador')
            ->select('tb_prestador.*')
            ->selectRaw('GROUP_CONCAT(tb_habilidades.idtb_habilidades) as habilidades_id')
            ->selectRaw('GROUP_CONCAT(tb_habilidades.Habilidade) as habilidades')
            ->join('tb_prestador_habilidade', 'tb_prestador.idtb_prestador', '=', 'tb_prestador_habilidade.tb_prestador_idtb_prestador')
            ->join('tb_habilidades', 'tb_prestador_habilidade.tb_habilidades_idtb_habilidades', '=', 'tb_habilidades.idtb_habilidades')
            ->groupBy('tb_prestador.idtb_prestador')
            ->where('tb_prestador.idtb_prestador', '=', $prestadorId)
            ->get();

        $prestadoresWithSkills = $results->map(function($result){
            $habilidadeIds = explode(',', $result->habilidades_id);
            $habilidades = explode(',', $result->habilidades);
            $skills = [];

            foreach($habilidadeIds as $index => $habilidadeId){
                $skills[] = [
                    'id' => $habilidadeId,
                    'habilidade' => $habilidades[$index],
                ];
            }

            $result->skills = $skills;
            unset($result->habilidades_id);
            unset($result->habilidades);
            return $result;
        });

        return $prestadoresWithSkills;
    }

    public function me(int $userId)
    {
        $results = DB::table('tb_prestador')
            ->select('tb_prestador.*')
            ->selectRaw('GROUP_CONCAT(tb_habilidades.idtb_habilidades) as habilidades_id')
            ->selectRaw('GROUP_CONCAT(tb_habilidades.Habilidade) as habilidades')
            ->join('tb_prestador_habilidade', 'tb_prestador.idtb_prestador', '=', 'tb_prestador_habilidade.tb_prestador_idtb_prestador')
            ->join('tb_habilidades', 'tb_prestador_habilidade.tb_habilidades_idtb_habilidades', '=', 'tb_habilidades.idtb_habilidades')
            ->groupBy('tb_prestador.idtb_prestador')
            ->where('tb_prestador_tb_user_idtb_user', '=', $userId)
            ->get();

        $prestadoresWithSkills = $results->map(function($result){
            $habilidadeIds = explode(',', $result->habilidades_id);
            $habilidades = explode(',', $result->habilidades);
            $skills = [];

            foreach($habilidadeIds as $index => $habilidadeId){
                $skills[] = [
                    'id' => $habilidadeId,
                    'habilidade' => $habilidades[$index],
                ];
            }

            $result->skills = $skills;
            unset($result->habilidades_id);
            unset($result->habilidades);
            return $result;
        });

        return $prestadoresWithSkills;
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

}