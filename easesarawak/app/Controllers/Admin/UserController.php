<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\User_model;
use App\Models\ActivityLogModel;
use App\Models\MessageModel;
class UserController extends BaseAdminController
{
    public function user()
    {
        $user_model = new User_model();
        $search     = trim($this->request->getGet('search') ?? '');
        $perPage    = 10;

        if ($search !== '') {
            $users = $user_model->where('is_deleted', 0)
                ->groupStart()
                    ->like('username', $search)
                    ->orLike('email', $search)
                ->groupEnd()
                ->findAll();
            $pager = null;
        } else {
            $users = $user_model->where('is_deleted', 0)->paginate($perPage, 'group1');
            $pager = $user_model->pager;
        }

        return $this->render('admin/user', [
            'users'         => $users,
            'pager'         => $pager,
            'search'        => $search,
            'currentUserId' => (int) session()->get('user_id'),
        ]);
    }

    public function create_user()
    {
        if (session()->get('role') !== '1') {
            return redirect()->to(base_url('/admin'))->with('error', 'Access denied. Superadmin only.');
        }

        $userModel = new User_model();

        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'role'     => 'required|in_list[0,1]',
                'username' => 'required|min_length[3]|max_length[100]',
                'email'    => 'required|valid_email|max_length[255]',
                'password' => [
                    'label'  => 'Password',
                    'rules'  => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/]',
                    'errors' => [
                        'min_length'  => 'Password must be at least 8 characters.',
                        'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
                    ],
                ],
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $username = trim($this->request->getPost('username'));
            $email    = trim($this->request->getPost('email'));

            if ($userModel->where('username', $username)->where('is_deleted', 0)->first()) {
                return redirect()->back()->withInput()->with('errors', ['username' => 'That username is already taken.']);
            }

            if ($userModel->where('email', $email)->where('is_deleted', 0)->first()) {
                return redirect()->back()->withInput()->with('errors', ['email' => 'That email is already registered.']);
            }

            $roleVal = $this->request->getPost('role');
            $userModel->insert([
                'role'         => $roleVal,
                'username'     => $username,
                'email'        => $email,
                'password'     => $this->request->getPost('password'),
                'created_date' => date('Y-m-d H:i:s'),
            ]);
            $newId = $userModel->getInsertID();

            return redirect()->to(base_url('/user'))->with('toast', [
                'title'   => 'User Created',
                'icon'    => 'fas fa-user-plus',
                'user_id' => (int) $newId,
                'username' => $username,
                'email'   => $email,
                'role'    => $roleVal == '1' ? 'Superadmin' : 'Admin',
            ]);
        }

        return $this->render('admin/create_user');
    }

    public function edit($user_id)
    {
        if (session()->get('role') !== '1') {
            return redirect()->to(base_url('/admin'))->with('error', 'Access denied. Superadmin only.');
        }

        $userModel = new User_model();
        $user = $userModel->find($user_id);

        if (!$user) {
            return redirect()->to(base_url('/user'))->with('error', 'User not found.');
        }
        return $this->render('admin/edit_user', ['target_user' => $user]);
    }

    public function update($user_id)
    {
        if (session()->get('role') !== '1') {
            return redirect()->to(base_url('/admin'))->with('error', 'Access denied. Superadmin only.');
        }

        $userModel = new User_model();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|max_length[255]',
            'role'     => 'required|in_list[0,1]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $username = trim($this->request->getPost('username'));
        $email    = trim($this->request->getPost('email'));

        $dupUsername = $userModel->where('username', $username)
            ->where('is_deleted', 0)
            ->where('user_id !=', $user_id)
            ->first();
        if ($dupUsername) {
            return redirect()->back()->withInput()->with('errors', ['username' => 'That username is already taken.']);
        }

        $dupEmail = $userModel->where('email', $email)
            ->where('is_deleted', 0)
            ->where('user_id !=', $user_id)
            ->first();
        if ($dupEmail) {
            return redirect()->back()->withInput()->with('errors', ['email' => 'That email is already registered.']);
        }

        $roleVal = $this->request->getPost('role');
        $userModel->update($user_id, [
            'username'      => $username,
            'email'         => $email,
            'role'          => $roleVal,
            'modified_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('/user'))->with('toast', [
            'title'   => 'User Updated',
            'icon'    => 'fas fa-user-edit',
            'user_id' => (int) $user_id,
            'username' => $username,
            'email'   => $email,
            'role'    => $roleVal == '1' ? 'Superadmin' : 'Admin',
        ]);
    }

    public function delete($user_id)
    {
        if (session()->get('role') !== '1') {
            return redirect()->to(base_url('/admin'))->with('error', 'Access denied. Superadmin only.');
        }

        if ((int) $user_id === (int) session()->get('user_id')) {
            return redirect()->to(base_url('/user'))->with('error', 'You cannot delete your own account.');
        }

        $userModel = new User_model();
        $user = $userModel->find($user_id);
        if (!$user) {
            return redirect()->to(base_url('/user'))->with('error', 'User not found.');
        }

        $userModel->update($user_id, [
            'is_deleted'    => 1,
            'modified_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('/user'))->with('toast', [
            'title'   => 'User Deleted',
            'icon'    => 'fas fa-trash-alt',
            'user_id' => (int) $user_id,
            'username' => $user['username'],
            'email'   => $user['email'],
            'role'    => $user['role'] == 1 ? 'Superadmin' : 'Admin',
        ]);
    }

}
