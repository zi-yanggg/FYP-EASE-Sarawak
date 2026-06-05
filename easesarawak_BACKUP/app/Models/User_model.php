<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'user_id';
    protected $allowedFields    = ['role', 'username',  'password', 'email', 'profile_picture', 'is_deleted', 
    'created_date', 'modified_date', 'reset_token', 'reset_expires', 'remember_token'];

    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
