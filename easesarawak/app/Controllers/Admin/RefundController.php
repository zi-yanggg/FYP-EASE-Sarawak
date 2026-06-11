<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class RefundController extends BaseAdminController
{
    public function refund_request()
    {
        $db      = \Config\Database::connect();
        $search  = trim($this->request->getGet('search') ?? '');
        $perPage = 10;
        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $offset  = ($page - 1) * $perPage;

        $builder = $db->table('refund_form rf')
            ->select('rf.*, u.username AS status_updated_username')
            ->join('user u', 'u.user_id = rf.status_updated_by', 'left')
            ->orderBy('rf.created_at', 'DESC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('rf.full_name', $search)
                ->orLike('rf.email', $search)
                ->orLike('rf.reason_for_refund', $search)
                ->groupEnd();
        }

        $total   = $builder->countAllResults(false);
        $refunds = $builder->limit($perPage, $offset)->get()->getResultArray();

        $pager = service('pager');
        $pager->store('group1', $page, $perPage, $total);

        return $this->render('admin/refund_request', [
            'refunds' => $refunds,
            'pager'   => $pager,
            'search'  => $search,
        ]);
    }

    public function change_refund_status()
    {
        $refundId  = (int) $this->request->getPost('refund_id');
        $newStatus = (int) $this->request->getPost('new_status');

        $statusMap = [
            0 => 'In Progress',
            1 => 'Approved',
            2 => 'Rejected',
        ];

        if (!$refundId || !isset($statusMap[$newStatus]) || $newStatus === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid refund status.'
            ]);
        }

        $db = \Config\Database::connect();

        $refund = $db->table('refund_form')
            ->where('id', $refundId)
            ->get()
            ->getRowArray();

        if (!$refund) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Refund record not found.'
            ]);
        }

        $userId   = session()->get('user_id');
        $username = session()->get('username') ?: 'Unknown';
        $now      = date('Y-m-d H:i:s');

        $updated = $db->table('refund_form')
            ->where('id', $refundId)
            ->update([
                'status_progress'   => $newStatus,
                'status_updated_by' => $userId,
                'status_updated_at' => $now,
            ]);

        if (!$updated) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unable to update refund status.'
            ]);
        }

        return $this->response->setJSON([
            'success'      => true,
            'status_label' => $statusMap[$newStatus],
            'username'     => $username,
            'updated_at'   => $now,
        ]);
    }

    public function viewPdf($refundId)
    {
        $refundService = new \App\Services\RefundService();
        $refund        = $refundService->findById((int) $refundId);

        if (! $refund) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Refund record not found.');
        }

        $pdfFile = $refundService->getPdfFilePath((int) $refundId);

        if (! file_exists($pdfFile)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('PDF file not found.');
        }

        return $this->response->download($pdfFile, null)->setFileName('refund_' . $refundId . '.pdf');
    }
}
