<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockModel extends Model
{
    protected $table = 'block';
    protected $primaryKey = 'id';
    protected $allowedFields = ['site_id', 'block_name', 'created_at', 'updated_at'];
    protected $useTimestamps = false;

    /**
     * Tüm blokları alır.
     *
     * @return array
     */
    public function getBlocks()
    {
        return $this->findAll();
    }

    /**
     * Belirli bir blok id'sine göre blok verisini alır.
     *
     * @param int $id
     * @return array
     */
    public function getBlockById($id)
    {
        return $this->find($id);
    }

    /**
     * Belirli bir site id'sine göre blokları alır.
     *
     * @param int $siteId
     * @return array
     */
    public function getBlockBySiteId($siteId)
    {
        return $this->where('site_id', $siteId)->findAll();
    }

    /**
     * Yeni blok kaydeder.
     *
     * @param array $data
     * @return bool
     */
    public function saveBlock($data)
    {
        return $this->insert($data);
    }

    /**
     * Mevcut bir blok kaydını günceller.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateBlock($id, $data)
    {
        return $this->update($id, $data);
    }
}
