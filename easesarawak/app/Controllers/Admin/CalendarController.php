<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class CalendarController extends BaseAdminController
{
    public function calendar(): string
    {
        $orderModel = new OrderModel();
        $orders = $orderModel
            ->select('order_id, first_name, last_name, service_type, status, order_details_json')
            ->where('is_deleted', 0)
            ->orderBy('created_date', 'DESC')
            ->findAll();

        $events = $this->buildCalendarEventsFromOrders($orders);

        $initialView = $this->request->getGet('view');
        $allowedViews = ['dayGridMonth', 'timeGridWeek', 'timeGridDay'];
        if (! in_array($initialView, $allowedViews, true)) {
            $initialView = 'dayGridMonth';
        }

        return $this->render('admin/calendar', [
            'calendar_events_json' => json_encode($events, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
            'initial_view'           => $initialView,
        ]);
    }
}
