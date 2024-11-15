<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DebtModel;
use App\Models\PaidDebtModel;
use App\Models\UserModel;

class UserController extends ResourceController{

    public function GetDebts(){
        $debtModel = new DebtModel();
        
        $viewedUserId = $this->request->getVar('viewed_user_id');
        $userId = $this->request->getVar('user_id');
        
        if ($viewedUserId != $userId) {
            return $this->respond([
                'status' => 403,
                'message' => 'Başka kullanıcının borçlarını görüntüleyemezsiniz.'
            ], 403);
        }
        
        $debts = $debtModel->where('user_id', $userId)->findAll();
        
        if ($debts) {
            return $this->respond([
                'status' => 200,
                'message' => 'Ödenmiş borçlar başarıyla alındı.',
                'data' => $debts
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Ödenmiş borç bulunamadı.'
            ], 404);
        }
    }
    
    public function postPayDebt(){

    $debtModel = new DebtModel();
    $paidDebtModel = new PaidDebtModel();
    
    $userId = $this->request->getVar('user_id');
    $adminId = $this->request->getVar('admin_id');
    $amount = $this->request->getVar('amount');
    
    $debt = $debtModel->where('user_id', $userId)->first();
    
    if ($debt) {
        $remainingDebt = $debt['amount'] - $amount;
        
        if ($remainingDebt > 0) {
            $debtModel->update($debt['id'], ['amount' => $remainingDebt]);
        } else {
            $debtModel->delete($debt['id']);
        }
        
        $paidDebtData = [
            'admin_id' => $adminId,
            'user_id' => $userId,
            'amount' => $amount,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $paidDebtModel->insert($paidDebtData);
        
        return $this->respond([
            'status' => 200,
            'message' => $remainingDebt > 0 ? 
                'Partial payment made. Remaining debt updated.' : 
                'Debt fully paid and removed from Debt table.',
            'remaining_debt' => $remainingDebt > 0 ? $remainingDebt : 0,
            'paid_data' => $paidDebtData
        ]);
    } else {
        return $this->respond([
            'status' => 404,
            'message' => 'Debt not found for the specified user.',
            'user_id' => $userId,
            'amount' => $amount
        ]);
    }
    }

    public function GetPaidDebts(){
        $paidDebtModel = new PaidDebtModel();
        
        $viewedUserId = $this->request->getVar('viewed_user_id');
        $userId = $this->request->getVar('user_id');
        
        if ($viewedUserId != $userId) {
            return $this->respond([
                'status' => 403,
                'message' => 'Başka kullanıcının borçlarını görüntüleyemezsiniz.'
            ], 403);
        }
        
        $paidDebts = $paidDebtModel->where('user_id', $userId)->findAll();
        
        if ($paidDebts) {
            return $this->respond([
                'status' => 200,
                'message' => 'Ödenmiş borçlar başarıyla alındı.',
                'data' => $paidDebts
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Ödenmiş borç bulunamadı.'
            ], 404);
        }
    }

    public function getUsersByFilters(){
        $userModel = new UserModel();

        $minAge = $this->request->getVar('minAge');
        $maxAge = $this->request->getVar('maxAge');
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        if (($startDate && !strtotime($startDate)) || ($endDate && !strtotime($endDate))) {
            return $this->respond([
                'status' => 400,
                'message' => 'Geçerli bir startDate ve endDate değeri giriniz. (Y-m-d formatında)'
            ], 400);
        }

        $builder = $userModel;

        if ($minAge !== null) {
            $builder->where('age >=', $minAge);
        }
        if ($maxAge !== null) {
            $builder->where('age <=', $maxAge);
        }

        if ($startDate && $endDate) {
            $builder->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate);
        }

        $users = $builder->findAll();

        if ($users) {
            return $this->respond([
                'status' => 200,
                'message' => 'Belirtilen filtrelere göre kullanıcılar listelendi.',
                'data' => $users
            ]);
        } else {
            return $this->respond([
                'status' => 404,
                'message' => 'Belirtilen filtrelere uygun kullanıcı bulunamadı.'
            ], 404);
        }
    }




}
