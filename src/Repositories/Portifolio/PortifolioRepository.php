<?php

namespace MiniRest\Repositories\Portifolio;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\AlbumPhotoNotFoundException;
use MiniRest\Exceptions\PortifolioPrestadorNotFoundException;
use MiniRest\Models\Portifolio\Portifolio;
use MiniRest\Models\Portifolio\PortifolioImage;

class PortifolioRepository
{
    private Portifolio $portifolio;
    private PortifolioImage $portifolioImage;

    public function __construct()
    {
        $this->portifolio = new Portifolio();
        $this->portifolioImage = new PortifolioImage();
    }

    public function store(array $gallery)
    {
        return $this->portifolio->insertGetId($gallery);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function verifyPortifolioOwner($userId, $portifolioId)
    {
        return $this->portifolio
            ->where('idtb_portifolio', $portifolioId)
            ->where('tb_prestador_tb_user_idtb_user', $userId)
            ->firstOrFail();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function verifyPortifolioId($portifolioId)
    {
        return $this->portifolio
            ->where('idtb_portifolio', $portifolioId)
            ->firstOrFail();
    }

    /**
     * @throws PortifolioPrestadorNotFoundException
     */
    public function update(array $gallery, $portifolioId)
    {
        try {
            $this->portifolio->where('idtb_portifolio', $portifolioId)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new PortifolioPrestadorNotFoundException('Portifólio não existe');
        }

        return $this->portifolio->where('idtb_portifolio', $portifolioId)->update($gallery);
    }

    /**
     * @throws AlbumPhotoNotFoundException
     */
    public function deleteAlbumPhoto($photoId, $prestadorId)
    {
        $status = $this->portifolioImage
            ->where('idtb_img_video', $photoId)
            ->where('tb_portifolio_tb_prestador_idtb_prestador', $prestadorId)
            ->delete();

        if ($status === 0) {
            throw new AlbumPhotoNotFoundException();
        }
    }

    /**
     * @throws PortifolioPrestadorNotFoundException|ModelNotFoundException
     */
    public function deleteAlbum($albumId, $prestadorId, $userId)
    {

//        $portifolioId = $this->verifyPortifolioId($albumId);
//
//        $this->verifyPortifolioOwner($userId, $portifolioId);

        $this->portifolioImage
            ->where('tb_portifolio_idtb_portifolio', $albumId)
            ->delete();

        $status = $this->portifolio
            ->where('idtb_portifolio', $albumId)
            ->where('tb_prestador_idtb_prestador', $prestadorId)
            ->delete();

        if ($status === 0) {
            throw new PortifolioPrestadorNotFoundException();
        }
    }

    public function storeGalleryItens(string $itenFileName, int $portifolioId, int $idPrestador, int $idUser)
    {
        return $this->portifolioImage->create([
            'Img' => $itenFileName,
            'tb_portifolio_idtb_portifolio' => $portifolioId,
            'tb_portifolio_tb_prestador_idtb_prestador' => $idPrestador,
            'tb_portifolio_tb_prestador_tb_user_idtb_user' => $idUser
        ]);
    }

    public function getAll(int $userId, string $base_url)
    {
        $portifolios = $this->portifolio->where('tb_prestador_tb_user_idtb_user', $userId)->get();
        $data = [];

        $portifolios->each(function ($item) use ($base_url) {
            $item->Capa = $base_url . '/' . $item->Capa;
        });

        foreach ($portifolios as $portifolio) {
            $portifolioPhotos = $this->portifolioImage->where('tb_portifolio_idtb_portifolio', $portifolio->idtb_portifolio)
                ->get(['idtb_img_video', 'Img']);

            $portifolioPhotos->each(function ($item) use ($base_url) {
                $item->Img = $base_url . '/' . $item->Img;
            });

            $data[] = [
                'portifolio' => $portifolio,
                'photos' => $portifolioPhotos
            ];
        }

        if (count($data) <= 0) throw new PortifolioPrestadorNotFoundException();

        return $data;
    }

    public function getAlbumById(int $portifolioId, string $base_url)
    {
        $portifolios = $this->portifolio->where('idtb_portifolio', $portifolioId)->get();
        $data = [];

        foreach ($portifolios as $portifolio) {

            $portifolioPhotos = $this->portifolioImage->where('tb_portifolio_idtb_portifolio', $portifolio->idtb_portifolio)
                ->get(['idtb_img_video', 'Img']);

            $portifolioPhotos->each(function ($item) use ($base_url) {
                $item->Img = $base_url . '/' . $item->Img;
            });

            $portifolios->each(function ($item) use ($base_url) {
                $item->Capa = $base_url . '/' . $item->Capa;
            });

            $data[] = [
                'portifolio' => $portifolio,
                'photos' => $portifolioPhotos
            ];
        }

        if (count($data) <= 0) throw new PortifolioPrestadorNotFoundException('Portifólio não existe');

        return $data[0];
    }
}