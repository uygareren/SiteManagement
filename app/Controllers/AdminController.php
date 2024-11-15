<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\BlockModel;
use App\Models\DebtModel;
use App\Models\FlatModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class AdminController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\AdminModel';
    protected $format    = 'json';

    public function PostAddDebt(){
        $adminModel = new AdminModel();
        $userModel = new UserModel();
        $debtModel = new DebtModel();

        $adminId = $this->request->getVar('admin_id');
        $userId = $this->request->getVar('user_id');
        $amount = $this->request->getVar('amount');
        $deadline = $this->request->getVar('deadline');
        
        if (!$adminId || !$userId || !$amount || !$deadline) {
            return $this->fail('Tüm alanlar doldurulmalıdır.', 400);
        }

        $deadline = date('Y-m-d H:i:s', strtotime($deadline));

        if (!$adminModel->find($adminId)) {
            return $this->failNotFound('Geçersiz admin ID.');
        }

        if (!$userModel->find($userId)) {
            return $this->failNotFound('Geçersiz kullanıcı ID.');
        }

        $data = [
            'admin_id' => $adminId,
            'user_id' => $userId,
            'amount' => $amount,
            'deadline' => $deadline,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($debtModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Borç başarıyla atandı']);
        } else {
            return $this->fail('Borç ataması başarısız oldu', 500);
        }
    }


    public function adminAssignFlatToUser(){

        $adminId = $this->request->getVar('admin_id');
        $userId = $this->request->getVar('user_id');
        $flatId = $this->request->getVar('flat_id');

        $adminModel = new AdminModel();
        $userModel = new UserModel();
        $flatModel = new FlatModel();

        // 1. Admin kontrolü
        $admin = $adminModel->find($adminId);
        if (!$admin) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz admin ID.',
            ]);
        }

        // 2. Flat kontrolü
        $flat = $flatModel->find($flatId);
        if (!$flat) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz flat ID.',
            ]);
        }

        // 3. Flat başka kullanıcıya atanmış mı kontrolü
        $existingUser = $userModel->where('flat_id', $flatId)->first();
        if ($existingUser) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Bu daire başka bir kullanıcıya atanmış.',
            ]);
        }

        // 4. Kullanıcı kontrolü
        $user = $userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz kullanıcı ID.',
            ]);
        }

        // 5. Flat atamasını gerçekleştirme
        $userModel->update($userId, ['flat_id' => $flatId]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Daire başarıyla atandı.',
        ]);
    }
}


