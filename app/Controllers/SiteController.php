<?php
namespace App\Controllers;

use App\Models\SiteModel;
use CodeIgniter\RESTful\ResourceController;

class SiteController extends ResourceController
{
    protected $modelName = 'App\Models\SiteModel';
    protected $format    = 'json';

    /**
     * Tüm siteleri JSON formatında döner.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function getSites(){
        $sites = $this->model->getSites();

        // Başarılı yanıt ile siteleri döndür
        return $this->respond([
            'status' => 200,
            'message' => 'Sites retrieved successfully',
            'data' => $sites
        ]);
    }


    /**
     * Belirli bir siteyi id ile alır.
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\Response
     */

    public function getSiteById($id){
        $site = $this->model->getSiteById($id);

        if ($site) {
            // Başarılı yanıt
            return $this->respond([
                'status' => 200,
                'message' => 'Site retrieved successfully',
                'data' => $site
            ]);
        } else {
            // Site bulunamadığında hata mesajı
            return $this->respond([
                'status' => 404,
                'message' => 'Site not found'
            ]);
        }
    }

      /**
     * Yeni bir site kaydeder.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function postSite(){
    $model = new SiteModel();

    // JSON formatındaki veriyi alıyoruz
    $data = $this->request->getJSON(true); // JSON verisini alıyoruz

    // Gelen veriyi kontrol ediyoruz
    if (empty($data['site_name']) || empty($data['type'])) {
        return $this->failValidationError('Site name and type are required.');
    }

    // Verinin doğruluğunu kontrol et ve siteyi kaydet
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

    public function updateSite($id)
    {
        $model = new SiteModel();
    
        // Gelen JSON verisini alıyoruz
        $data = $this->request->getJSON(true); // JSON verisini alıyoruz
    
        // Verilerin doğruluğunu kontrol et
        if (empty($data['site_name']) || empty($data['type'])) {
            return $this->failValidationError('Site name and type are required.');
        }
    
        // Mevcut siteyi kontrol et
        $site = $model->getSiteById($id); // id'ye ait siteyi al
    
        if (!$site) {
            return $this->failNotFound('Site with the specified ID not found.');
        }
    
        // Güncellenme zamanını ekliyoruz
        $data['updated_at'] = date('Y-m-d H:i:s'); // Güncelleme zamanı
    
        // Mevcut siteyi güncelle
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
    






}


