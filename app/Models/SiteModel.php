<?php
// app/Models/SiteModel.php
namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model
{
    protected $table = 'site';
    protected $primaryKey = 'id';
    protected $allowedFields = ['site_name', 'type', 'created_at'];

    /**
     * Tüm site kayıtlarını getirir.
     *
     * @return array
     */
    public function getSites()
    {
        return $this->findAll();
    }

 /**
     * Belirli bir id'ye sahip siteyi getirir.
     *
     * @param int $id
     * @return array
     */

    public function getSiteById($id)
    {
        return $this->where('id', $id)->first();
    }

  /**
     * Yeni site kaydetme fonksiyonu.
     *
     * @param array $data
     * @return bool
     */
    public function saveSite($data)
    {
        return $this->save($data);
    }
 /**
     * Mevcut siteyi güncelleme fonksiyonu.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateSite($id, $data)
    {
        return $this->update($id, $data);
    }
}