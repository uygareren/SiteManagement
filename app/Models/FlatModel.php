<?php

namespace App\Models;

use CodeIgniter\Model;

class FlatModel extends Model{
    protected $table = 'Flat'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['block_id', 'flat_no', 'room_number', 'created_at'];

    public function getAllFlats()
    {
        return $this->findAll();
    }

    public function getFlatById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function saveFlat($data)
    {
        return $this->save($data);
    }

    public function updateFlat($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteFlat($id)
    {
        return $this->delete($id);
    }

}
