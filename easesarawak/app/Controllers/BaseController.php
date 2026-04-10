<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\User_model;
use App\Models\MessageModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['translation'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    protected $currentUser;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Load current user
        $userId = session()->get('user_id');
        if ($userId) {
            $userModel = new User_model();
            $this->currentUser = $userModel->where([
                'user_id'    => $userId,
                'is_deleted' => 0
            ])->first();
        }

        // Remember Me functionality
        if (session()->get('access') !== 1) {
            $token = cookie('remember_me');
            if ($token) {
                $userModel = new User_model();
                $user = $userModel->where('remember_token', $token)->first();
                if ($user) {
                    session()->set([
                        'user_id' => $user['user_id'],
                        'username'=> $user['username'],
                        'email'   => $user['email'],
                        'access'  => 1,
                        'role'    => $user['role']
                    ]);
                }
            }
        }

        // Apply site locale from query/session/cookie on every request
        $locale = $request->getGet('lang')
            ?? session()->get('site_lang')
            ?? $request->getCookie('site_lang')
            ?? config('App')->defaultLocale;

        if (function_exists('normalize_site_locale')) {
            $locale = normalize_site_locale($locale);
        }

        $request->setLocale($locale);
    }

    protected function render($view, $data = [])
    {
        $data['user'] = $this->currentUser;

        $messageModel = new MessageModel();
        $data['headerMessages'] = $messageModel
            ->where('is_deleted', 0)
            ->orderBy('created_date', 'DESC')
            ->limit(5)
            ->findAll();

        $data['newMessageCount'] = count(array_filter($data['headerMessages'], function($msg) {
            $status = trim((string) ($msg['status'] ?? ''));
            return $status === '' || $status === 'new';
        }));

        return view($view, $data);
    }
}
