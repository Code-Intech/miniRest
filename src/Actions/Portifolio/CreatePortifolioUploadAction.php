<?php

namespace MiniRest\Actions\Portifolio;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\DTO\Portifolio\PortifolioCreateDTO;
use MiniRest\Exceptions\PrestadorNotFoundException;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Repositories\Portifolio\PortifolioRepository;
use MiniRest\Repositories\Prestador\PrestadorRepository;
use MiniRest\Storage\Acl\PublicAcl;
use MiniRest\Storage\S3Storage;
use MiniRest\Storage\UUIDFileName;

class CreatePortifolioUploadAction
{
    public function __construct()
    {
    }

    /**
     * @throws UploadErrorException|PrestadorNotFoundException
     */
    public function execute(PortifolioCreateDTO $createDTO, int $userId)
    {
        try {

            $coverFileName = UUIDFileName::uuidFileName($createDTO->toArray()['Capa']['name']);
            $portifolioRespository = new PortifolioRepository();

            try {
                $prestadorId = (new PrestadorRepository())->getPrestadorId($userId);

            } catch (ModelNotFoundException) {
                throw new PrestadorNotFoundException();
            }

            $protifolioId = $portifolioRespository->store([
                'Capa' => $coverFileName,
                'Titulo' => $createDTO->toArray()['Titulo'],
                'Descricao' => $createDTO->toArray()['Descricao'],
                'tb_prestador_idtb_prestador' => $prestadorId,
                'tb_prestador_tb_user_idtb_user' => $userId
            ]);

            $upload = new S3Storage(new PublicAcl());
            $upload->upload(
                'portifolio/' . $coverFileName,
                $createDTO->toArray()['Capa']['tmp_name']
            );

            foreach ($upload->reArrayFiles($createDTO->toArray()['Img']) as $file) {
                $itenFileName = UUIDFileName::uuidFileName($file['name']);

                $upload->upload('portifolio/' . $itenFileName, $file['tmp_name']);

                $portifolioRespository->storeGalleryItens(
                    $itenFileName,
                    $protifolioId,
                    $prestadorId,
                    $userId
                );
            }

        } catch (\PDOException $e) {
            throw new UploadErrorException($e->getMessage());
        }

    }
}