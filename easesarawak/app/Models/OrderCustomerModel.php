<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderCustomerModel extends Model
{
    protected $table            = 'order_customer';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'order_id',
        'first_name',
        'last_name',
        'id_num',
        'email',
        'phone',
        'social',
        'social_num',
        'upload',
        'special',
        'special_note',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
}
