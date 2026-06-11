<?php

namespace App\Services;

class RefundService
{
    private const PDF_DIR = 'uploads/refunds/';

    /**
     * @param array<string, mixed> $data
     * @return array{success: bool, refund_id?: int, access_token?: string, message?: string}
     */
    public function submit(array $data): array
    {
        $db = \Config\Database::connect();

        $accessToken = bin2hex(random_bytes(32));
        $data['access_token'] = $accessToken;

        try {
            $db->transStart();

            $refundTable = $db->table('refund_form');
            if (! $refundTable->insert($data)) {
                $db->transRollback();

                return ['success' => false, 'message' => 'Refund form submission failed.'];
            }

            $refundId  = (int) $db->insertID();
            $createdAt = date('Y-m-d H:i:s');
            $pdfPath   = $this->generatePdf($refundId, $data, $createdAt);

            $refundTable->where('id', $refundId)->update([
                'pdf_path' => $pdfPath,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return ['success' => false, 'message' => 'Refund form submission failed.'];
            }

            return [
                'success'      => true,
                'refund_id'    => $refundId,
                'access_token' => $accessToken,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'RefundService submit error: {msg}', ['msg' => $e->getMessage()]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $refundId): ?array
    {
        $row = \Config\Database::connect()
            ->table('refund_form')
            ->where('id', $refundId)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function verifyAccess(int $refundId, ?string $token): bool
    {
        $refund = $this->findById($refundId);
        if (! $refund) {
            return false;
        }

        $stored = (string) ($refund['access_token'] ?? '');
        if ($stored === '') {
            return false;
        }

        return hash_equals($stored, (string) $token);
    }

    public function getPdfFilePath(int $refundId): string
    {
        return FCPATH . self::PDF_DIR . 'refund_' . $refundId . '.pdf';
    }

    /**
     * @param array<string, mixed> $data
     */
    private function generatePdf(int $refundId, array $data, string $createdAt): string
    {
        $pdfDirectory = FCPATH . self::PDF_DIR;
        if (! is_dir($pdfDirectory)) {
            mkdir($pdfDirectory, 0755, true);
        }

        $fileName    = 'refund_' . $refundId . '.pdf';
        $pdfFullPath = $pdfDirectory . $fileName;

        $pdf = new \TCPDF();
        $pdf->SetCreator('EASE Sarawak');
        $pdf->SetAuthor('EASE Sarawak');
        $pdf->SetTitle('Ease Sarawak|Refund Form #' . $refundId);
        $pdf->SetSubject('Refund Form Submission');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        $html = '
            <h2 style="text-align:center;">EASE SARAWAK REFUND FORM</h2>
            <table border="1" cellpadding="6">
                <tr><td width="35%"><b>Refund ID</b></td><td width="65%">' . esc((string) $refundId) . '</td></tr>
                <tr><td><b>Full Name</b></td><td>' . esc($data['full_name']) . '</td></tr>
                <tr><td><b>Email</b></td><td>' . esc($data['email']) . '</td></tr>
                <tr><td><b>Phone Number</b></td><td>' . esc($data['phone_number']) . '</td></tr>
                <tr><td><b>Order ID</b></td><td>' . esc($data['order_id']) . '</td></tr>
                <tr><td><b>Date of Purchase</b></td><td>' . esc((string) $data['date_of_purchase']) . '</td></tr>
                <tr><td><b>Service Type</b></td><td>' . esc($data['service_type']) . '</td></tr>
                <tr><td><b>Bank Name</b></td><td>' . esc($data['bank_name']) . '</td></tr>
                <tr><td><b>Account Holder Name</b></td><td>' . esc($data['account_holder_name']) . '</td></tr>
                <tr><td><b>Account Number</b></td><td>' . esc($data['account_number']) . '</td></tr>
                <tr><td><b>Reason for Refund</b></td><td>' . nl2br(esc($data['reason_for_refund'])) . '</td></tr>
                <tr><td><b>Declaration</b></td><td>' . ($data['declaration'] ? 'Agreed' : 'Not agreed') . '</td></tr>
                <tr><td><b>Created At</b></td><td>' . esc($createdAt) . '</td></tr>
            </table>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($pdfFullPath, 'F');

        return self::PDF_DIR . $fileName;
    }
}
