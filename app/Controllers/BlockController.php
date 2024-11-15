<?php

namespace App\Controllers;

use App\Models\BlockModel;
use CodeIgniter\RESTful\ResourceController;

class BlockController extends ResourceController
{
    protected $modelName = 'App\Models\BlockModel';
    protected $format    = 'json';

    public function getBlocks()
    {
        $blocks = $this->model->getBlocks();

        return $this->respond([
            'status' => 200,
            'message' => 'Blocks retrieved successfully',
            'data' => $blocks
        ]);
    }

    public function getBlockById($id)
    {
        $block = $this->model->getBlockById($id);

        if ($block) {
            return $this->respond([
                'status' => 200,
                'message' => 'Block retrieved successfully',
                'data' => $block
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Block not found'
            ]);
        }
    }

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

    public function postBlock()
    {
        $data = $this->request->getJSON(true); 

        if (empty($data['site_id']) || empty($data['block_name'])) {
            return $this->failValidationError('Site ID and Block Name are required.');
        }

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

    public function updateBlock($id){
        $data = $this->request->getJSON(true); 

        if (empty($data['site_id']) || empty($data['block_name'])) {
            return $this->failValidationError('Site ID and Block Name are required.');
        }

        $block = $this->model->getBlockById($id); 

        if (!$block) {
            return $this->failNotFound('Block with the specified ID not found.');
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

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

    public function deleteBlock($id){
        $model = new BlockModel();

        $block = $model->getBlockById($id);

        if(!$block){
            return $this->response->setStatusCode(404)
            ->setJSON([
                'status'  => 404,
                'message' => 'Block not found.'
            ]);
        }

        $deleted = $model->deleteBlock($id);

        if ($deleted) {
            return $this->response->setStatusCode(200)
                ->setJSON([
                    'status'  => 200,
                    'message' => 'Block deleted successfully.'
                ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'  => 500,
                    'message' => 'Failed to delete Block.'
                ]);
        }

    }
}
