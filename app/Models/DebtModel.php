<?php

namespace App\Models;

use CodeIgniter\Model;

class DebtModel extends Model{
    protected $table = 'Debt';
    protected $primaryKey = 'id';
    protected $allowedFields = ['admin_id', 'user_id', 'amount', 'deadline', 'created_at'];
    protected $useTimestamps = false;
}
