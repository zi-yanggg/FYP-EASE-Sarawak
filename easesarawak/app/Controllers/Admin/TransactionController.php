<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class TransactionController extends BaseAdminController
{
    public function transaction_history()
    {
        $paymentModel = new PaymentModel();
        $search = trim($this->request->getGet('search') ?? '');

        if ($search !== '') {
            $paymentModel->groupStart()
                ->like('stripe_payment_id', $search)
                ->orLike('payment_intent_id', $search)
                ->orLike('status', $search)
                ->groupEnd();
        }

        $transactions = $paymentModel->orderBy('created_at', 'DESC')->paginate(10, 'group1');
        $pager = $paymentModel->pager;

        return $this->render('admin/transaction_history', [
            'transactions' => $transactions,
            'pager'        => $pager,
            'search'       => $search,
        ]);
    }

}
