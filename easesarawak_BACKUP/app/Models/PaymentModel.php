<?php namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'payment_intent_id';
    protected $useAutoIncrement = false;

    protected $returnType       = 'array';

    protected $allowedFields = [
        'stripe_payment_id',   
        'payment_intent_id',   
        'amount_cents',
        'currency',
        'status',
        'created_at',
    ];

    protected $useTimestamps = false;
}
