<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class ContactController extends BaseAdminController
{
    public function contact()
    {
        $messageModel = new MessageModel();
        $perPage = 10;
        $filter = $this->request->getGet('filter');
        $messageId = $this->request->getGet('message_id');

        if ($messageId) {
            $messageModel->update($messageId, ['status' => 'read']);
        }

        $query = $messageModel->where('is_deleted', 0);

        // Apply status filter
        if ($filter === 'new') {
            $query->where('status', 'new');
        } elseif ($filter === 'read') {
            $query->where('status', 'read');
        }

        $messages = $query
            ->orderBy('created_date', 'DESC')
            ->paginate($perPage, 'group1');

        $pager = $messageModel->pager;

        return $this->render('admin/contact', [
            'messages' => $messages,
            'pager'    => $pager,
        ]);
    }

    public function markMessageRead($msg_id)
    {
        $messageModel = new MessageModel();
        $messageModel->update($msg_id, ['status' => 'read']);
        return $this->response->setJSON(['success' => true]);
    }

    public function markAllMessagesRead()
    {
        $messageModel = new MessageModel();
        $messageModel->where('is_deleted', 0)
            ->where('status !=', 'read')
            ->set(['status' => 'read'])
            ->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function getMessage($msg_id)
    {
        $messageModel = new MessageModel();
        $message = $messageModel->find($msg_id);
        if ($message) {
            return $this->response->setJSON(['success' => true, 'message' => $message]);
        }
        return $this->response->setJSON(['success' => false]);
    }

}
