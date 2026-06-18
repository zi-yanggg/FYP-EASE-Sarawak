<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
use App\Services\OrderDetailsService;
class OrderController extends BaseAdminController
{
    public function order($id = null)
    {
        helper('form');
        
        $status = $this->request->getGet('status');
        $service = $this->request->getGet('service_type');
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        $orderModel = new OrderModel();

        // If specific order ID is provided, show that order
        if ($id !== null) {
            $order = $orderModel->find($id);
            if ($order) {
                $data['orders'] = [$order];
                $data['pager'] = null;
                $data['single_order'] = true;
                return $this->render('admin/order', $data);
            }
        }

        // Otherwise, show the order list with filters
        $orderModel->where('1=1'); // base

        if ($status !== null && $status !== '') {
            $orderModel->where('status', $status);
        }

        if ($service !== null && $service !== '') {
            $orderModel->where('service_type', $service);
        }

        if (!empty($start)) {
            $orderModel->where('created_date >=', $start . ' 00:00:00');
        }

        if (!empty($end)) {
            $orderModel->where('created_date <=', $end . ' 23:59:59');
        }

        $orders = $orderModel->where('is_deleted', 0)->paginate(10, 'group1');
        $detailsService = new OrderDetailsService();
        $bookingMap     = $detailsService->mapBookingsByOrderId($orders);
        $enrichedOrders = [];

        foreach ($orders as $order) {
            $bookingRow       = $bookingMap[(int) ($order['order_id'] ?? 0)] ?? [];
            $enrichedOrders[] = $detailsService->enrichOrderRow($order, $bookingRow);
        }

        $data['orders'] = $enrichedOrders;
        $data['pager'] = $orderModel->pager;
        $data['single_order'] = false;

        return $this->render('admin/order', $data);
    }

    public function order_activity_log($order_id)
    {
        $logModel = new ActivityLogModel();

        $logs = $logModel->where('order_id', $order_id)
            ->orderBy('modified_date', 'DESC')
            ->findAll();

        return $this->render('admin/activity_log_table', ['logs' => $logs]);
    }

    public function change_status($order_id)
    {
        $orderModel = new OrderModel();
        $logModel = new ActivityLogModel();
        $session = session();
        $userId = $session->get('user_id');

        $order = $orderModel->find($order_id);

        if ($order) {
            $statusText = [
                0 => 'Pending',
                1 => 'In Progress',
                2 => 'Completed'
            ];

            // Cycle through status: 0 → 1 → 2 → 0
            $newStatus = ($order['status'] + 1) % 3;
            $orderModel->update($order_id, ['status' => $newStatus,
                    'modified_by' => $userId,
                    'modified_date' => date('Y-m-d H:i:s')]);

            $logModel->insert([
                'order_id' => $order_id,
                'user_id' => $userId,
                'username' => session()->get('username'),
                'action' => 'Changed order status to ' . $statusText[$newStatus],
                'modified_date' => date('Y-m-d H:i:s')
            ]);
            
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'     => true,
                'new_status'  => $newStatus,
                'status_text' => $statusText[$newStatus],
            ]);
        }

        session()->setFlashdata('order_status_success', [
            'order_id' => $order_id,
            'status'   => $statusText[$newStatus],
            'username' => session()->get('username') ?: 'Unknown'
        ]);
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found.']);
            }
            session()->setFlashdata('error', 'Order not found.');
        }

        return redirect()->to(base_url('/order'));
    }

    public function getDetails($order_id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderWithUserById($order_id);

        if ($order) {
            $detailsService = new OrderDetailsService();
            $bookingMap     = $detailsService->mapBookingsByOrderId([$order]);
            $bookingRow     = $bookingMap[(int) $order['order_id']] ?? [];
            $order          = $detailsService->enrichOrderRow($order, $bookingRow);

            return $this->response->setJSON([
                'success' => true,
                'order' => $order
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }
    }

    public function order_details($order_id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderWithUserById($order_id);

        if (!$order) {
            return redirect()->to(base_url('/admin/refund_request'))
                ->with('error', 'Order not found.');
        }

        $detailsService = new OrderDetailsService();
        $bookingMap     = $detailsService->mapBookingsByOrderId([$order]);
        $bookingRow     = $bookingMap[(int) $order['order_id']] ?? null;
        $details        = $detailsService->displayDetails($order, $bookingRow);

        return $this->render('admin/order_details', [
            'order'   => $order,
            'details' => $details
        ]);
    }

    public function save_note()
    {
        $orderId = (int) $this->request->getPost('order_id');
        $note    = trim($this->request->getPost('note') ?? '');
        $userId  = session()->get('user_id');

        if ($orderId <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid order ID.']);
        }

        if (strlen($note) > 1000) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Note must be 1000 characters or fewer.']);
        }

        $orderModel = new OrderModel();
        $existing   = $orderModel->find($orderId);
        $prevNote   = trim($existing['comment'] ?? '');

        $orderModel->update($orderId, [
            'comment'       => $note,
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_by'   => $userId,
        ]);

        if ($note === '') {
            $action     = 'Deleted note';
            $actionType = 'deleted';
        } elseif ($prevNote === '') {
            $action     = 'Added note: "' . $note . '"';
            $actionType = 'added';
        } else {
            $action     = 'Edited note: "' . $note . '"';
            $actionType = 'edited';
        }

        $logModel = new ActivityLogModel();
        $logModel->insert([
            'order_id'      => $orderId,
            'user_id'       => $userId,
            'username'      => session()->get('username'),
            'action'        => $action,
            'modified_date' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => 'success', 'action' => $actionType]);
    }

}
