<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class ServiceController extends BaseAdminController
{
    public function service_management()
    {
        $model = new \App\Models\ServiceManagementModel();
        $services = $model->findAll();
        return $this->render('admin/service_management', ['services' => $services]);
    }

    public function update_service_price($id)
    {
        $base_price = $this->request->getPost('base_price');
        $extra_rate = $this->request->getPost('extra_rate');

        if (!is_numeric($base_price) || (int)$base_price < 1) {
            return redirect()->to('/admin/service_management')
                ->with('toast', [
                    'title'   => 'Invalid Price',
                    'icon'    => 'fas fa-exclamation-circle',
                    'message' => 'Base price must be greater than zero.',
                ]);
        }

        if (!is_numeric($extra_rate) || (int)$extra_rate < 1) {
            return redirect()->to('/admin/service_management')
                ->with('toast', [
                    'title'   => 'Invalid Rate',
                    'icon'    => 'fas fa-exclamation-circle',
                    'message' => 'Extra rate must be greater than zero.',
                ]);
        }

        $model = new \App\Models\ServiceManagementModel();
        $model->update($id, [
            'base_price' => (int)$base_price,
            'extra_rate' => (int)$extra_rate,
        ]);

        return redirect()->to('/admin/service_management')
            ->with('toast', [
                'title'   => 'Pricing Updated',
                'icon'    => 'fas fa-check-circle',
                'message' => 'Service pricing has been updated successfully.',
            ]);
    }

}
