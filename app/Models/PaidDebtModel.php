<?php

namespace App\Models;

use CodeIgniter\Model;

class PaidDebtModel extends Model{
    protected $table = 'Paid_debt';
    protected $primaryKey = 'id';
    protected $allowedFields = ['admin_id', 'user_id', 'amount', 'created_at'];
    protected $useTimestamps = false;
}
