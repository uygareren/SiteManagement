<?php

namespace App\Controllers;

use App\Models\BlockModel;
use CodeIgniter\RESTful\ResourceController;

class BlockController extends ResourceController
{
    protected $modelName = 'App\Models\BlockModel';
    protected $format    = 'json';

    /**
     * Tüm blokları JSON formatında döner.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function getBlocks()
    {
        $blocks = $this->model->getBlocks();

        // Başarılı yanıt ile blokları döndür
        return $this->respond([
            'status' => 200,
            'message' => 'Blocks retrieved successfully',
            'data' => $blocks
        ]);
    }

    /**
     * Belirli bir blok ID'sine göre blok verisini alır.
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\Response
     */
    public function getBlockById($id)
    {
        $block = $this->model->getBlockById($id);

        if ($block) {
            // Başarılı yanıt
            return $this->respond([
                'status' => 200,
                'message' => 'Block retrieved successfully',
                'data' => $block
            ]);
        } else {
            // Blok bulunamadığında hata mesajı
            return $this->respond([
                'status' => 404,
                'message' => 'Block not found'
            ]);
        }
    }

    /**
     * Belirli bir site ID'sine göre blokları alır.
     *
     * @param int $siteId
     * @return \CodeIgniter\HTTP\Response
     */
    public function getBlocksBySiteId($siteId)
    {
        $blocks = $this->model->getBlockBySiteId($siteId);

        if (count($blocks) > 0) {
            return $this->respond([
                'status' => 200,
                'message' => 'Blocks retrieved successfully',
                'data' => $blocks
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'No blocks found for the specified site ID'
            ]);
        }
    }

    /**
     * Yeni bir blok kaydeder.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function postBlock()
    {
        // Gelen JSON verisini alıyoruz
        $data = $this->request->getJSON(true); // JSON verisini alıyoruz

        // Gelen veriyi kontrol ediyoruz
        if (empty($data['site_id']) || empty($data['block_name'])) {
            return $this->failValidationError('Site ID and Block Name are required.');
        }

        // Verinin doğruluğunu kontrol et ve blok kaydet
        if ($this->model->saveBlock($data)) {
            return $this->respondCreated([
                'status' => 201,
                'message' => 'Block created successfully',
                'data' => $data
            ]);
        } else {
            return $this->failServerError('Failed to create block.');
        }
    }

    /**
     * Varolan bir bloğu günceller.
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateBlock($id)
    {
        // Gelen JSON verisini alıyoruz
        $data = $this->request->getJSON(true); // JSON verisini alıyoruz

        // Verilerin doğruluğunu kontrol et
        if (empty($data['site_id']) || empty($data['block_name'])) {
            return $this->failValidationError('Site ID and Block Name are required.');
        }

        // Mevcut bloğu kontrol et
        $block = $this->model->getBlockById($id); // id'ye ait bloğu al

        if (!$block) {
            return $this->failNotFound('Block with the specified ID not found.');
        }

        // Güncellenme zamanını ekliyoruz
        $data['updated_at'] = date('Y-m-d H:i:s'); // Güncelleme zamanı

        // Mevcut bloğu güncelle
        if ($this->model->updateBlock($id, $data)) {
            return $this->respond([
                'status' => 200,
                'message' => 'Block updated successfully',
                'data' => $data
            ]);
        } else {
            return $this->failServerError('Failed to update block.');
        }
    }
}
