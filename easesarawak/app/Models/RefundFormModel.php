<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Encrypts sensitive financial fields before DB insert/update,
 * decrypts them transparently on read.
 *
 * Encrypted fields: account_number, account_holder_name, bank_name
 */
class RefundFormModel extends Model
{
    protected $table      = 'refund_form';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'full_name', 'email', 'phone_number', 'order_id',
        'date_of_purchase', 'service_type',
        'bank_name', 'account_holder_name', 'account_number',
        'reason_for_refund', 'declaration', 'pdf_path', 'status',
    ];

    // Fields encrypted in the DB column (stored as base64-encoded ciphertext)
    private array $encryptedFields = [
        'account_number',
        'account_holder_name',
        'bank_name',
    ];

    private function encrypter()
    {
        return \Config\Services::encrypter();
    }

    protected $beforeInsert = ['encryptSensitiveFields'];
    protected $beforeUpdate = ['encryptSensitiveFields'];
    protected $afterFind    = ['decryptSensitiveFields'];

    protected function encryptSensitiveFields(array $data): array
    {
        if (!isset($data['data'])) {
            return $data;
        }

        $enc = $this->encrypter();

        foreach ($this->encryptedFields as $field) {
            if (isset($data['data'][$field]) && $data['data'][$field] !== '') {
                $data['data'][$field] = base64_encode(
                    $enc->encrypt($data['data'][$field])
                );
            }
        }

        return $data;
    }

    protected function decryptSensitiveFields(array $data): array
    {
        if (empty($data['data'])) {
            return $data;
        }

        $enc = $this->encrypter();

        // Handle both single row and multiple rows
        $rows = isset($data['data'][0]) ? $data['data'] : [$data['data']];

        foreach ($rows as &$row) {
            foreach ($this->encryptedFields as $field) {
                if (!empty($row[$field])) {
                    try {
                        $row[$field] = $enc->decrypt(base64_decode($row[$field]));
                    } catch (\Exception $e) {
                        // Field may be legacy unencrypted — leave as-is
                        log_message('warning', "Could not decrypt refund field '{$field}' for row id={$row['id']}");
                    }
                }
            }
        }

        $data['data'] = isset($data['data'][0]) ? $rows : $rows[0];

        return $data;
    }
}
