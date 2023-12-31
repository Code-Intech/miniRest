<?php

namespace MiniRest\Repositories\Servico;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\AccessNotAllowedException;
use MiniRest\Models\Servico\Servico;
use MiniRest\Models\Servico\ServicoUploadImage;
use MiniRest\Models\Servico\ServicoFinalizado;
use MiniRest\Exceptions\DatabaseInsertException;
use MiniRest\Exceptions\ServiceNotFoundedException;
use MiniRest\Exceptions\ImagesNotFoundException;
use MiniRest\Helpers\StatusCode\StatusCode;
use Illuminate\Database\Capsule\Manager as DB;
use MiniRest\Repositories\ContratanteRepository;


class ServicoRepository
{
    private $model;
    private $modelFinaliza;
    private $imagesModel;

    public function __construct()
    {
        $this->model = new Servico();
        $this->imagesModel = new ServicoUploadImage();
        $this->modelFinaliza = new ServicoFinalizado();
    }

    /**
     * @param int $userId
     * @throws AccessNotAllowedException
     */
    public function verifyServiceOwner(int $userId, int $imageId)
    {
        try {
            // model do serviço :/
            $contratanteId = (new ContratanteRepository())->getContratanteIdByUserId($userId);
            $this->imagesModel
                ->where('tb_servico_tb_contratante_idtb_contratante', $contratanteId)
                ->where('idtb_img', $imageId)
                ->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new AccessNotAllowedException('Você não possui acesso para deletar a imagem selecionada');
        }
    }

    /**
     * @param int $imageId
     * @throws ImagesNotFoundException
     */
    public function getImageById(int $imageId)
    {
        try {
            $this->imagesModel
                ->where('idtb_img', $imageId)
                ->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new ImagesNotFoundException('Imagem não encontrada.');
        }
    }

    public function getAll()
    {
        $servicos = Servico::where('FlgStatus', '<>', 0)->get();
        $data = [];

        foreach ($servicos as $servico) {
            $servicoAll = Servico::select('idtb_servico', 'tb_contratante_idtb_contratante', 'tb_contratante_tb_user_idtb_user', 'Titulo_Servico', 'Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc', 'created_at')
                ->where('idtb_servico', $servico->idtb_servico)
                ->first();

            $localidade = DB::table('tb_servico')
                ->select('Cidade', 'Estado', 'Bairro')
                ->join('tb_end', 'tb_end.idtb_end', '=', 'tb_servico.tb_end_idtb_end')
                ->where('tb_servico.idtb_servico', $servico->idtb_servico)
                ->first();

            $contratante = DB::table('tb_user')
                ->select('Nome_Completo', 'idtb_prestador')
                ->leftJoin('tb_prestador', 'tb_user.idtb_user', '=', 'tb_prestador.tb_user_idtb_user')
                ->join('tb_contratante', 'tb_user.idtb_user', '=', 'tb_contratante.tb_user_idtb_user')
                ->join('tb_servico', 'tb_contratante.idtb_contratante', '=', 'tb_servico.tb_contratante_idtb_contratante')
                ->where('tb_servico.idtb_servico', $servico->idtb_servico)
                ->first();

            $profissoes = DB::table('tb_servico_profissao')
                ->select('Profissao', 'idtb_profissoes', 'Categoria', 'tb_categoria_idtb_categoria')
                ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_servico_profissao.tb_profissoes_idtb_profissoes')
                ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
                ->where('tb_servico_profissao.tb_servico_idtb_servico', $servico->idtb_servico)
                ->get();


            $habilidades = DB::table('tb_servico_habilidade')
                ->select('Habilidade', 'idtb_habilidades')
                ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_servico_habilidade.tb_habilidades_idtb_habilidades')
                ->where('tb_servico_habilidade.tb_servico_idtb_servico', $servico->idtb_servico)
                ->get();


            $data[] = [
                'contratante' => $contratante,
                'servicoInfo' => $servicoAll,
                'localidade' => $localidade,
                'servicoProfessions' => $profissoes,
                'servicoSkills' => $habilidades,
            ];
        }

        return $data;
    }

    public function find(int|string $servicoId)
    {

        $servicoAll = Servico::select('idtb_servico', 'tb_contratante_idtb_contratante', 'tb_contratante_tb_user_idtb_user', 'Titulo_Servico', 'Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc', 'created_at')
            ->where('idtb_servico', $servicoId)
            ->where('FlgStatus', '<>', 0)
            ->first();

        $localidade = DB::table('tb_servico')
            ->select('Cidade', 'Estado', 'Bairro')
            ->join('tb_end', 'tb_end.idtb_end', '=', 'tb_servico.tb_end_idtb_end')
            ->where('tb_servico.idtb_servico', $servicoId)
            ->first();

        $contratante = DB::table('tb_user')
            ->select('Nome_Completo', 'idtb_prestador')
            ->leftJoin('tb_prestador', 'tb_user.idtb_user', '=', 'tb_prestador.tb_user_idtb_user')
            ->join('tb_contratante', 'tb_user.idtb_user', '=', 'tb_contratante.tb_user_idtb_user')
            ->join('tb_servico', 'tb_contratante.idtb_contratante', '=', 'tb_servico.tb_contratante_idtb_contratante')
            ->where('tb_servico.idtb_servico', $servicoId)
            ->first();

        $profissoes = DB::table('tb_servico_profissao')
            ->select('Profissao', 'idtb_profissoes', 'Categoria', 'tb_categoria_idtb_categoria')
            ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_servico_profissao.tb_profissoes_idtb_profissoes')
            ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
            ->where('tb_servico_profissao.tb_servico_idtb_servico', $servicoId)
            ->get();


        $habilidades = DB::table('tb_servico_habilidade')
            ->select('Habilidade', 'idtb_habilidades')
            ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_servico_habilidade.tb_habilidades_idtb_habilidades')
            ->where('tb_servico_habilidade.tb_servico_idtb_servico', $servicoId)
            ->get();

        return [
            'contratante' => $contratante,
            'servicoInfo' => $servicoAll,
            'localidade' => $localidade,
            'servicoProfessions' => $profissoes,
            'servicoSkills' => $habilidades,
        ];
    }

    /**
     * @throws ServiceNotFoundedException
     */
    public function me(int $userId)
    {
        $servico = Servico::where('tb_contratante_tb_user_idtb_user', $userId)
            ->where('FlgStatus', '<>', 0)
            ->get();

        $contratanteId = (new ContratanteRepository())->getContratanteIdByUserId($userId);

        $data = [];

        foreach ($servico as $servicos) {
            $servicoAll = Servico::select('idtb_servico', 'tb_contratante_idtb_contratante', 'tb_contratante_tb_user_idtb_user', 'Titulo_Servico', 'Data_Inicio', 'Estimativa_de_distancia', 'Estimativa_Valor', 'Estimativa_Idade', 'Remoto_Presencial', 'Estimativa_de_termino', 'Desc', 'created_at')
                ->where('idtb_servico', $servicos->idtb_servico)
                ->first();

            $localidade = DB::table('tb_servico')
                ->select('tb_end.*')
                ->join('tb_end', 'tb_end.idtb_end', '=', 'tb_servico.tb_end_idtb_end')
                ->where('tb_servico.idtb_servico', $servicos->idtb_servico)
                ->first();

            $contratante = DB::table('tb_user')
                ->select('Nome_Completo', 'idtb_prestador')
                ->leftJoin('tb_prestador', 'tb_user.idtb_user', '=', 'tb_prestador.tb_user_idtb_user')
                ->leftJoin('tb_contratante', 'tb_user.idtb_user', '=', 'tb_contratante.tb_user_idtb_user')
                ->where('tb_contratante.idtb_contratante', $contratanteId)
                ->first();

            $profissoes = DB::table('tb_servico_profissao')
                ->select('Profissao', 'idtb_profissoes', 'Categoria', 'tb_categoria_idtb_categoria')
                ->join('tb_profissoes', 'tb_profissoes.idtb_profissoes', '=', 'tb_servico_profissao.tb_profissoes_idtb_profissoes')
                ->join('tb_categoria', 'tb_categoria.idtb_categoria', '=', 'tb_profissoes.tb_categoria_idtb_categoria')
                ->where('tb_servico_profissao.tb_servico_idtb_servico', $servicos->idtb_servico)
                ->get();


            $habilidades = DB::table('tb_servico_habilidade')
                ->select('Habilidade', 'idtb_habilidades')
                ->join('tb_habilidades', 'tb_habilidades.idtb_habilidades', '=', 'tb_servico_habilidade.tb_habilidades_idtb_habilidades')
                ->where('tb_servico_habilidade.tb_servico_idtb_servico', $servicos->idtb_servico)
                ->get();

            $data [] = [
                'contratante' => $contratante,
                'servicoInfo' => $servicoAll,
                'localidade' => $localidade,
                'servicoProfessions' => $profissoes,
                'servicoSkills' => $habilidades,
            ];
        }

        if (count($data) <= 0) throw new ServiceNotFoundedException('Você não possui serviços');

        return $data;


    }

    public function storeServico(array $data)
    {
        try {
            $servico = $this->model->create($data);
            return $servico;
        } catch (\Exception $e) {
            throw new DatabaseInsertException("Erro ao criar serviço.", StatusCode::SERVER_ERROR, $e);
        }
    }

    public function updateServico(array $data)
    {
        $servicoId = $data['idtb_servico'];
        try {
            return DB::transaction(function () use ($data, $servicoId) {
                $servico = $this->model->where('idtb_servico', $servicoId)->update($data);
                return $servico;
            });
        } catch (\Exception $e) {
            throw new DatabaseInsertException("Erro ao atualizar serviço.", StatusCode::SERVER_ERROR, $e->getMessage());
        }
    }

    public function deleteServico($servicoId)
    {
        try {
            return DB::transaction(function () use ($servicoId) {
                $servico = $this->model->where('idtb_servico', $servicoId)->first();
                if ($servico) {
                    $servico->update(['FlgStatus' => 0]);
                    return true;
                }
                return false;
            });
        } catch (\Exception $e) {
            throw new DatabaseInsertException("Erro ao deletar serviço.", StatusCode::SERVER_ERROR, $e);
        }

    }

    public function storeImages(string $itenFileName, int $servicoId, int $contratanteId, int $userId)
    {
        return $this->imagesModel->create([
            'IMG' => $itenFileName,
            "tb_servico_idtb_servico" => $servicoId,
            "tb_servico_tb_contratante_idtb_contratante" => $contratanteId,
            "tb_servico_tb_contratante_tb_user_idtb_user" => $userId,
        ]);
    }

    public function deleteImages(int $servicoId)
    {
        try {
            return DB::transaction(function () use ($servicoId) {
                $images = $this->imagesModel->where('tb_servico_idtb_servico', $servicoId);
                if ($images) {
                    $images->delete();
                }
            });
        } catch (\Exception $e) {
            throw new ImagesNotFoundException("Imagens não encontradas");
        }


    }

    /**
     * @param $id
     * @param $userId
     * @throws ImagesNotFoundException
     */
    public function deleteImageByImageId($id, $userId)
    {
        try {
            $this->imagesModel->where('idtb_img', $id)->firstOrFail()->delete();
        } catch (ModelNotFoundException $exception) {
            throw new ImagesNotFoundException('Imagem não encontrada!');
        }

    }

    public function getServicoId(int $servicoId)
    {
        $servico = $this->model->where('idtb_servico', $servicoId)->first();
        if ($servico) {
            return $servico->idtb_servico;
        } else {
            throw new ServiceNotFoundedException("Serviço não encontrado");
        }
    }

    public function getServicoUser(int $servicoId)
    {
        $servico = $this->model->where('idtb_servico', $servicoId)->value('tb_contratante_tb_user_idtb_user');
        if ($servico) {
            return $servico;
        } else {
            throw new ServiceNotFoundedException("Serviço não enonttrado");
        }
    }

    /**
     * @throws ImagesNotFoundException
     */
    public function getServicoImages(int $servicoId)
    {
        $images = DB::table('tb_img')
            ->select('idtb_img as imageId', DB::raw("CONCAT('https://s3connectfreela.s3.sa-east-1.amazonaws.com/servico/', `IMG`) as image_url"))
            ->where('tb_servico_idtb_servico', $servicoId)
            ->get();

        if (count($images) <= 0) throw new ImagesNotFoundException('O serviço não possui imagens');

        return $images;

    }

    public function finalizaServico(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $servico = $this->modelFinaliza->create($data);
                return $servico;
            });
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            throw new DatabaseInsertException("Erro ao finalizar serviço.", StatusCode::SERVER_ERROR, $e->getMessage());
        }
    }
}