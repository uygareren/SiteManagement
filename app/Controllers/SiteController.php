<?php
namespace App\Controllers;

use App\Models\BlockModel;
use App\Models\SiteModel;
use CodeIgniter\RESTful\ResourceController;

class SiteController extends ResourceController
{
    protected $modelName = 'App\Models\SiteModel';
    protected $format    = 'json';

    public function getSites()
    {
        $siteModel = new SiteModel();
        $blockModel = new BlockModel();
        
        $sites = $siteModel->getSites();

        foreach ($sites as &$site) {
            $blocks = $blockModel->getBlockBySiteId($site['id']);
            $blockCount = count($blocks);
            
            if ($blockCount == 1) {
                $site['type'] = 'Apartment'; 
            } elseif ($blockCount > 1) {
                $site['type'] = 'Site';
            }
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Sites retrieved successfully',
            'data' => $sites
        ]);
    }

    public function getSiteById($id){
        $siteModel = new SiteModel();
        $blockModel = new BlockModel();
    
        $site = $siteModel->getSiteById($id);
    
        if ($site) {
            $blocks = $blockModel->getBlockBySiteId($id);
            $blockCount = count($blocks);
            
            if ($blockCount == 1) {
                $site['type'] = 'Apartment'; 
            } elseif ($blockCount > 1) {
                $site['type'] = 'Site';
            } else {
                $site['type'] = 'Site'; 
            }
    
            return $this->respond([
                'status' => 200,
                'message' => 'Site retrieved successfully',
                'data' => $site
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Site not found'
            ]);
        }
    }
    

    public function postSite(){
    $model = new SiteModel();

    $data = $this->request->getJSON(true);

    if (empty($data['site_name']) || empty($data['type'])) {
        return $this->failValidationError('Site name and type are required.');
    }

    if ($model->saveSite($data)) {
        return $this->respondCreated([
            'status' => 201,
            'message' => 'Site created successfully',
            'data' => $data
        ]);
    } else {
        return $this->failServerError('Failed to create site.');
    }
    }

    public function updateSite($id){
        $model = new SiteModel();
    
        $data = $this->request->getJSON(true); 
    
        if (empty($data['site_name']) || empty($data['type'])) {
            return $this->failValidationError('Site name and type are required.');
        }
    
        $site = $model->getSiteById($id);
    
        if (!$site) {
            return $this->failNotFound('Site with the specified ID not found.');
        }
    
        $data['updated_at'] = date('Y-m-d H:i:s'); // Güncelleme zamanı
    
        if ($model->updateSite($id, $data)) {
            return $this->respond([
                'status' => 200,
                'message' => 'Site updated successfully',
                'data' => $data
            ]);
        } else {
            return $this->failServerError('Failed to update site.');
        }
    }

    public function deleteSite($id){
        $model = new SiteModel();
        
        $flat = $model->getSiteById($id);

        if (!$flat) {
            return $this->response->setStatusCode(404)
                ->setJSON([
                    'status'  => 404,
                    'message' => 'Site not found.'
                ]);
        }

        $deleted = $model->deleteSite($id);

        if ($deleted) {
            return $this->response->setStatusCode(200)
                ->setJSON([
                    'status'  => 200,
                    'message' => 'Site deleted successfully.'
                ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'  => 500,
                    'message' => 'Failed to delete Site.'
                ]);
        }
    }

}


