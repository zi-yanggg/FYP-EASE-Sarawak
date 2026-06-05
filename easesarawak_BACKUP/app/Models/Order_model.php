<?php

namespace App\Models;

use CodeIgniter\Model;

class Order_model extends Model
{
    protected $table            = 'order';
    protected $primaryKey       = 'order_id';
    protected $allowedFields    = ['service_type', 'first_name', 'last_name', 'id_num', 'email', 'phone',
    'social', '	social_num', '	upload', '	special', 'special_note', 'order_details_json', 'promo_code', 'status', 
    'amount', '	payment_method', 'modified_by', 'comment', 'is_deleted', 'created_date', 'modified_date'];

    public function getOrderWithUserById($order_id)
    {
        return $this->db->table('`order` o')
            ->select('o.*, u.username AS modified_by_username')
            ->join('user u', 'u.user_id = o.modified_by', 'left')
            ->where('o.order_id', $order_id)
            ->where('o.is_deleted', 0)
            ->get()
            ->getRowArray(); // getRowArray() returns only one row
    }
}