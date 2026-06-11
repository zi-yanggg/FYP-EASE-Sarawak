<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderBookingModel extends Model
{
    protected $table            = 'order_booking';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'order_id',
        'booking_json',
        'dropoff_at',
        'pickup_at',
        'origin',
        'destination',
        'storage_location',
        'quantity',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
}
