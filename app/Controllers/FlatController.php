<?php

namespace App\Controllers;

use App\Models\FlatModel;
use CodeIgniter\RESTful\ResourceController;

class FlatController extends ResourceController
{
    protected $modelName = 'App\Models\FlatModel';
    protected $format    = 'json';

    public function getAllFlats()
    {
        $flats = $this->model->getAllFlats();

        return $this->respond([
            'status' => 200,
            'message' => 'Flats retrieved successfully',
            'data' => $flats
        ]);
    }

    public function getFlatById($id)
    {
        $flat = $this->model->getFlatById($id);

        if ($flat) {
            return $this->respond([
                'status' => 200,
                'message' => 'Flat retrieved successfully',
                'data' => $flat
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Flat not found'
            ]);
        }
    }

    public function getFlatsByBlockId($block_id)
    {
        $flats = $this->model->getFlatByBlockId($block_id);

        if ($flats) {
            return $this->respond([
                'status' => 200,
                'message' => 'Flats retrieved successfully for block_id ' . $block_id,
                'data' => $flats
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'No flats found for the given block_id'
            ]);
        }
    }

   
    public function postFlat(){
        $model = new FlatModel();

        $data = $this->request->getJSON(true); // JSON verisini alıyoruz

        if (empty($data['block_id']) || empty($data['flat_no']) || empty($data['room_number'])) {
            return $this->response->setStatusCode(400)
            ->setJSON([
                'status'  => 400,
                'message' => 'Block ID, Flat Number, and Room Number are required.'
            ]);
        }

        $existingFlat = $model->where('block_id', $data['block_id'])
                              ->where('flat_no', $data['flat_no'])
                              ->first();

        if ($existingFlat) {
            return $this->response->setStatusCode(409)
                                  ->setJSON([
                                      'status'  => 409,
                                      'message' => 'Flat with the same block_id and flat_no already exists.'
                                  ]);
        }

        if ($model->save($data)) {
            return $this->response->setStatusCode(201)
                ->setJSON([
                    'status'  => 201,
                    'message' => 'Flat created successfully',
                    'data'    => $data
                ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'  => 500,
                    'message' => 'Failed to create flat.'
                ]);
        }
    }

    public function updateFlat($id){
        $model = new FlatModel();
        
        $data = $this->request->getJSON(true);

        $flat = $model->getFlatById($id);

        if (!$flat) {
            return $this->response->setStatusCode(404)
            ->setJSON([
                'status'  => 404,
                'message' => 'Flat not found.'
            ]);
        }

        $updated = $model->updateFlat($id, $data);

        if ($updated) {
            return $this->response->setStatusCode(200)
                ->setJSON([
                    'status'  => 200,
                    'message' => 'Flat updated successfully.',
                    'data'    => $data
                ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'  => 500,
                    'message' => 'Failed to update flat.'
                ]);
        }
    }
    public function getFlatsByDateAndRoom()
    {
        $model = new FlatModel();
    
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $min_rooms = $this->request->getGet('min_rooms');
        $max_rooms = $this->request->getGet('max_rooms');
    
        $filters = [];
    
        if (!empty($min_rooms)) {
            $filters['room_number >='] = $min_rooms;  
        }
        if (!empty($max_rooms)) {
            $filters['room_number <='] = $max_rooms;  
        }
    
        if (!empty($start_date) && !empty($end_date)) {
            $filters['created_at >='] = $start_date;
            $filters['created_at <='] = $end_date;
        }
    
        if (empty($filters)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'status'  => 400,
                    'message' => 'Please provide at least one filter parameter.'
                ]);
        }
    
        $flats = $model->where($filters)->findAll();
    
        if ($flats) {
            return $this->respond([
                'status' => 200,
                'message' => 'Flats retrieved successfully',
                'data' => $flats
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'No flats found with the given filters.'
            ]);
        }
    }

 
    public function deleteFlat($id){
        $model = new FlatModel();
        
        $flat = $model->getFlatById($id);

        if (!$flat) {
            return $this->response->setStatusCode(404)
                ->setJSON([
                    'status'  => 404,
                    'message' => 'Flat not found.'
                ]);
        }

        $deleted = $model->deleteFlat($id);

        if ($deleted) {
            return $this->response->setStatusCode(200)
                ->setJSON([
                    'status'  => 200,
                    'message' => 'Flat deleted successfully.'
                ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'  => 500,
                    'message' => 'Failed to delete flat.'
                ]);
        }
    }


   
    
}
