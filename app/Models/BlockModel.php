<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockModel extends Model{
    protected $table = 'block';
    protected $primaryKey = 'id';
    protected $allowedFields = ['site_id', 'block_name', 'created_at', 'updated_at'];
    protected $useTimestamps = false;

    public function getBlocks()
    {
        return $this->findAll();
    }

    public function getBlockById($id)
    {
        return $this->find($id);
    }

    public function getBlockBySiteId($siteId)
    {
        return $this->where('site_id', $siteId)->findAll();
    }

    public function saveBlock($data)
    {
        return $this->insert($data);
    }

    public function updateBlock($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteBlock($id){
        return $this->delete($id);
    }

}
