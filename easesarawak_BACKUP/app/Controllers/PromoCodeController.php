<?php
namespace App\Controllers;

use App\Models\PromoCodeModel;

class PromoCodeController extends BaseController
{
    public function index()
    {
        $model = new PromoCodeModel();
        $promoCodes = $model->where('is_deleted', 0)
                            ->orderBy('created_date', 'DESC')
                            ->findAll();

        return $this->render('admin/promo_code', ['promoCodes' => $promoCodes]);
    }

    public function create()
    {
        return $this->render('admin/add_promo');
    }

    public function store()
    {
        $rules = [
            'code' => 'required|max_length[100]',
            'discount_type' => 'required|in_list[percentage,amount]',
            'discount_percentage' => 'permit_empty|integer|less_than_equal_to[100]',
            'discount_amount' => 'permit_empty|decimal',
            'validation_date' => 'required',
            'expired_date' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new PromoCodeModel();

        $type = $this->request->getPost('discount_type');
        $data = [
            'code' => $this->request->getPost('code'),
            'discount_type' => $type,
            'discount_percentage' => $type === 'percentage' ? $this->request->getPost('discount_percentage') : 0,
            'discount_amount' => $type === 'amount' ? $this->request->getPost('discount_amount') : 0,
            'validation_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('validation_date'))),
            'expired_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('expired_date'))),
            'is_deleted' => 0,
            'created_date' => date('Y-m-d H:i:s')
        ];

        $model->insert($data);

        return redirect()->to(base_url('/admin/promo_code'))->with('success', 'Promo created successfully');
    }

    public function edit($id = null)
    {
        $model = new PromoCodeModel();
        $promo = $model->find($id);

        if (! $promo) {
            return redirect()->to(base_url('/admin/promo_code'))->with('error', 'Promo not found');
        }

        return $this->render('admin/edit_promo', ['promo' => $promo]);
    }

    public function update($id = null)
    {
        $rules = [
            'code' => 'required|max_length[100]',
            'discount_type' => 'required|in_list[percentage,amount]',
            'discount_percentage' => 'permit_empty|integer|less_than_equal_to[100]',
            'discount_amount' => 'permit_empty|decimal',
            'validation_date' => 'required',
            'expired_date' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new PromoCodeModel();

        $type = $this->request->getPost('discount_type');
        $data = [
            'code' => $this->request->getPost('code'),
            'discount_type' => $type,
            'discount_percentage' => $type === 'percentage' ? $this->request->getPost('discount_percentage') : 0,
            'discount_amount' => $type === 'amount' ? $this->request->getPost('discount_amount') : 0,
            'validation_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('validation_date'))),
            'expired_date' => date('Y-m-d H:i:s', strtotime($this->request->getPost('expired_date'))),
            'modified_date' => date('Y-m-d H:i:s')
        ];

        $model->update($id, $data);

        return redirect()->to(base_url('/admin/promo_code'))->with('success', 'Promo updated successfully');
    }

    public function storeAjax()
    {
        $rules = [
            'code'                => 'required|max_length[100]',
            'discount_type'       => 'required|in_list[percentage,amount]',
            'discount_percentage' => 'permit_empty|integer|less_than_equal_to[100]',
            'discount_amount'     => 'permit_empty|decimal',
            'validation_date'     => 'required',
            'expired_date'        => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => array_values($this->validator->getErrors()),
            ]);
        }

        $model = new PromoCodeModel();
        $code  = $this->request->getPost('code');

        $existing = $model->where('code', $code)
                          ->where('is_deleted', 0)
                          ->where('expired_date >=', date('Y-m-d H:i:s'))
                          ->first();

        if ($existing) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['This promo code is already active. Use a different code or wait for it to expire.'],
            ]);
        }

        $type = $this->request->getPost('discount_type');

        $model->insert([
            'code'                => $code,
            'discount_type'       => $type,
            'discount_percentage' => $type === 'percentage' ? $this->request->getPost('discount_percentage') : 0,
            'discount_amount'     => $type === 'amount'     ? $this->request->getPost('discount_amount')     : 0,
            'validation_date'     => date('Y-m-d H:i:s', strtotime($this->request->getPost('validation_date'))),
            'expired_date'        => date('Y-m-d H:i:s', strtotime($this->request->getPost('expired_date'))),
            'is_deleted'          => 0,
            'created_date'        => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function updateAjax($id = null)
    {
        $rules = [
            'code'                => 'required|max_length[100]',
            'discount_type'       => 'required|in_list[percentage,amount]',
            'discount_percentage' => 'permit_empty|integer|less_than_equal_to[100]',
            'discount_amount'     => 'permit_empty|decimal',
            'validation_date'     => 'required',
            'expired_date'        => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => array_values($this->validator->getErrors()),
            ]);
        }

        $model = new PromoCodeModel();
        $code  = $this->request->getPost('code');

        $existing = $model->where('code', $code)
                          ->where('is_deleted', 0)
                          ->where('expired_date >=', date('Y-m-d H:i:s'))
                          ->where('id !=', $id)
                          ->first();

        if ($existing) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['This promo code is already active. Use a different code or wait for it to expire.'],
            ]);
        }

        $type = $this->request->getPost('discount_type');

        $model->update($id, [
            'code'                => $code,
            'discount_type'       => $type,
            'discount_percentage' => $type === 'percentage' ? $this->request->getPost('discount_percentage') : 0,
            'discount_amount'     => $type === 'amount'     ? $this->request->getPost('discount_amount')     : 0,
            'validation_date'     => date('Y-m-d H:i:s', strtotime($this->request->getPost('validation_date'))),
            'expired_date'        => date('Y-m-d H:i:s', strtotime($this->request->getPost('expired_date'))),
            'modified_date'       => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id = null)
    {
        $model = new PromoCodeModel();
        $promo = $model->find($id);
        if ($promo) {
            $model->update($id, ['is_deleted' => 1, 'modified_date' => date('Y-m-d H:i:s')]);
        }
        return redirect()->to(base_url('/admin/promo_code'))->with('success', 'Promo deleted');
    }
}