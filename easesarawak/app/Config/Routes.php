<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Public marketing & booking ────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/policy', 'Home::policy');
$routes->get('/terms-and-conditions', 'Home::tnc');
$routes->get('/booking', 'Home::booking');
$routes->get('/bookingdetail', 'Home::bookingdetail');
$routes->get('/bookingcustomerdetail', 'Home::bookingcustomerdetail');
$routes->post('/saveOrder', 'Home::saveOrder', ['filter' => 'apiThrottle']);
$routes->post('/refund/submit', 'Home::submitRefund', ['filter' => 'apiThrottle']);
$routes->get('refund/view/(:num)', 'Home::viewRefundPdf/$1');
$routes->post('/message', 'Home::message', ['filter' => 'apiThrottle']);
$routes->post('/checkPromoCode', 'Home::checkPromoCode', ['filter' => 'apiThrottle']);

// ── Payment flow ────────────────────────────────────────────────────
$routes->get('/booking_confirmation', 'Home::booking_confirmation');
$routes->match(['get', 'post'], '/payment', 'Home::payment');
$routes->post('webhook', 'CardPayment::webhook');
$routes->post('card-payment/intent', 'CardPayment::createIntent', ['filter' => 'apiThrottle']);
$routes->post('card-payment/store', 'CardPayment::store', ['filter' => 'apiThrottle']);
$routes->post('send-receipt', 'Receipt::send', ['filter' => 'apiThrottle']);

// ── Auth ────────────────────────────────────────────────────────────
$routes->get('/login', 'Login::index');
$routes->post('/login_submit', 'Login::submit');
$routes->get('/logout', 'Login::logout');
$routes->get('forgot_password', 'AuthController::forgotPasswordForm');
$routes->post('forgot_password', 'AuthController::forgotPassword', ['filter' => 'apiThrottle']);
$routes->get('reset_password/(:any)', 'AuthController::resetPasswordForm/$1');
$routes->post('reset_password/(:any)', 'AuthController::resetPassword/$1', ['filter' => 'apiThrottle']);

// ── Language ────────────────────────────────────────────────────────
$routes->get('language/(:segment)', 'LanguageController::change/$1');

// ── Admin portal (authenticated) ────────────────────────────────────
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/admin', 'Admin\DashboardController::index');
    $routes->get('/report', 'Admin\ReportController::report');
    $routes->get('/report/export', 'Admin\ReportController::exportRevenue');
    $routes->get('/admin/getRevenueData', 'Admin\ReportController::getRevenueData');
    $routes->get('/admin/getPeakTimesData', 'Admin\ReportController::getPeakTimesData');

    $routes->get('/order', 'Admin\OrderController::order');
    $routes->get('/order/(:num)', 'Admin\OrderController::order/$1');
    $routes->post('/change_status/(:num)', 'Admin\OrderController::change_status/$1');
    $routes->get('/order/getDetails/(:num)', 'Admin\OrderController::getDetails/$1');
    $routes->get('/admin/order_details/(:num)', 'Admin\OrderController::order_details/$1');
    $routes->post('/save_note', 'Admin\OrderController::save_note');
    $routes->get('order_activity_log/(:num)', 'Admin\OrderController::order_activity_log/$1');

    $routes->get('/admin/calendar', 'Admin\CalendarController::calendar');
    $routes->get('/transaction_history', 'Admin\TransactionController::transaction_history');

    $routes->get('/admin/contact', 'Admin\ContactController::contact');
    $routes->post('/admin/markMessageRead/(:num)', 'Admin\ContactController::markMessageRead/$1');
    $routes->post('/admin/markAllMessagesRead', 'Admin\ContactController::markAllMessagesRead');
    $routes->get('/admin/getMessage/(:num)', 'Admin\ContactController::getMessage/$1');

    $routes->get('/admin/refund_request', 'Admin\RefundController::refund_request');
    $routes->post('/admin/refund_request/change_status', 'Admin\RefundController::change_refund_status');
    $routes->get('admin/refund/view/(:num)', 'Admin\RefundController::viewPdf/$1');

    $routes->get('/admin/service_management', 'Admin\ServiceController::service_management');
    $routes->post('/admin/service_management/update/(:num)', 'Admin\ServiceController::update_service_price/$1');

    $routes->get('/profile', 'Profile::profile');
    $routes->get('/edit_profile/(:num)', 'Profile::edit_profile/$1');
    $routes->post('/update_profile/(:num)', 'Profile::update_profile/$1');
    $routes->get('/change_password', 'Profile::change_password_form');
    $routes->post('/change_password', 'Profile::change_password');

    $routes->get('/admin/promo_code', 'PromoCodeController::index');
    $routes->get('/admin/promo_code/create', 'PromoCodeController::create');
    $routes->post('/admin/promo_code/store', 'PromoCodeController::store');
    $routes->post('/admin/promo_code/store_ajax', 'PromoCodeController::storeAjax');
    $routes->get('/admin/promo_code/edit/(:num)', 'PromoCodeController::edit/$1');
    $routes->post('/admin/promo_code/update/(:num)', 'PromoCodeController::update/$1');
    $routes->post('/admin/promo_code/update_ajax/(:num)', 'PromoCodeController::updateAjax/$1');
    $routes->get('/admin/promo_code/delete/(:num)', 'PromoCodeController::delete/$1');
});

// ── Superadmin-only routes ──────────────────────────────────────────
$routes->group('', ['filter' => 'auth|role'], static function ($routes) {
    $routes->get('/user', 'Admin\UserController::user');
    $routes->match(['get', 'post'], '/create_user', 'Admin\UserController::create_user');
    $routes->get('/edit_user/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('/update_user/(:num)', 'Admin\UserController::update/$1');
    $routes->get('/delete_user/(:num)', 'Admin\UserController::delete/$1');
});

// ── Health check ────────────────────────────────────────────────────
$routes->get('health', static function () {
    $dbOk = false;
    try {
        \Config\Database::connect()->query('SELECT 1');
        $dbOk = true;
    } catch (\Throwable) {
        $dbOk = false;
    }

    return service('response')->setJSON([
        'status'   => $dbOk ? 'ok' : 'degraded',
        'database' => $dbOk ? 'connected' : 'unavailable',
        'time'     => date('c'),
    ]);
});
