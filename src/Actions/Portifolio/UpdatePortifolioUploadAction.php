<?php

namespace MiniRest\Actions\Portifolio;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\DTO\Portifolio\PortifolioCreateDTO;
use MiniRest\DTO\Portifolio\PortifolioUpdateDTO;
use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Exceptions\PortifolioUpdateNotAllowedException;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;

class UpdatePortifolioUploadAction
{
    public function __construct()
    {
    }

    /**
     * @throws UploadErrorException|PrestadorNotFoundException|PortifolioPrestadorNotFoundException|PortifolioUpdateNotAllowedException
     */
    public function execute(PortifolioUpdateDTO $updateDTO, int $userId, $portifolioId)
    {
        try {

            $coverFileName = UUIDFileName::uuidFileName($updateDTO->toArray()['Capa']['name']);
            $portifolioRespository = new PortifolioRepository();

            try {
                $portifolioRespository->verifyPortifolioOwner($userId, $portifolioId);
            } catch (ModelNotFoundException) {
                throw new PortifolioUpdateNotAllowedException();
            }

            try {
                $prestadorId = (new PrestadorRepository())->getPrestadorId($userId);
            } catch (ModelNotFoundException) {
                throw new PrestadorNotFoundException();
            }

            $portifolioRespository->update([
                'Capa' => $coverFileName,
                'Titulo' => $updateDTO->toArray()['Titulo'],
                'Descricao' => $updateDTO->toArray()['Descricao'],
                'tb_prestador_idtb_prestador' => $prestadorId,
                'tb_prestador_tb_user_idtb_user' => $userId
            ], $portifolioId);

            $upload = new S3Storage(new PublicAcl());
            $upload->upload(
                'portifolio/' . $coverFileName,
                $updateDTO->toArray()['Capa']['tmp_name']
            );

        } catch (\PDOException $e) {
            throw new UploadErrorException($e->getMessage());
        }

    }
}