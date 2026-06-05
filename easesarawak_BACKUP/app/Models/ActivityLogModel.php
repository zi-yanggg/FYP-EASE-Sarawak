<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_log';
    protected $primaryKey       = 'log_id';
    protected $allowedFields    = [
        'order_id',
        'user_id',
        'username',
        'action',
        'modified_date',
    ];
}
