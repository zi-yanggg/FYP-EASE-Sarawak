<?php
namespace App\Models;

use CodeIgniter\Model;

class PromoCodeModel extends Model
{
    protected $table      = 'promo_code';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'code','discount_type','discount_percentage','discount_amount','validation_date','expired_date',
        'is_deleted','created_date','modified_date'
    ];
}