<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
use App\Services\OrderDetailsService;
class CalendarController extends BaseAdminController
{
    public function calendar(): string
    {
        $db = \Config\Database::connect();

        $builder = $db->table('`order` o')
            ->select('o.order_id, o.first_name, o.last_name, o.service_type, o.status, o.order_details_json, b.dropoff_at, b.pickup_at, b.booking_json')
            ->where('o.is_deleted', 0)
            ->orderBy('o.created_date', 'DESC');

        if ($db->tableExists('order_booking')) {
            $builder->join('order_booking b', 'b.order_id = o.order_id', 'left');
        } else {
            $builder->select('o.order_id, o.first_name, o.last_name, o.service_type, o.status, o.order_details_json');
        }

        $orders = $builder->get()->getResultArray();
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
