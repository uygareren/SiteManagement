<?php
namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model{
    protected $table = 'site';
    protected $primaryKey = 'id';
    protected $allowedFields = ['site_name', 'created_at', 'updated_at'];

    public function getSites(){
        return $this->findAll();
    }

    public function getSiteById($id){
        return $this->where('id', $id)->first();
    }

    public function saveSite($data){
        return $this->save($data);
    }

    public function updateSite($id, $data){
        return $this->update($id, $data);
    }

    public function deleteSite($id){
        return $this->delete($id);
    }
}