<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'message';
    protected $primaryKey       = 'msg_id';
    protected $allowedFields    = [
        'email',
        'phone',
        'subject',
        'msg',
        'status',
        'is_deleted',
        'created_date',
        'modified_date',
    ];
}
