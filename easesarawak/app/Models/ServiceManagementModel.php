<?php
namespace App\Models;

use CodeIgniter\Model;

class ServiceManagementModel extends Model
{
    protected $table      = 'service_management';
    protected $primaryKey = 'id';
    protected $allowedFields = ['service_type', 'base_price'];
}