<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Intro-Landing Page routes
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/policy', 'Home::policy');
$routes->get('/terms-and-conditions', 'Home::tnc');
$routes->get('/booking', 'Home::booking');
$routes->get('/bookingdetail', 'Home::bookingdetail');
$routes->get('/bookingcustomerdetail', 'Home::bookingcustomerdetail');
$routes->post('/saveOrder', 'Home::saveOrder');
$routes->post('/refund/submit', 'Home::submitRefund');
$routes->get('refund/view/(:num)', 'Home::viewRefundPdf/$1');

// payment
$routes->get('/booking_confirmation', 'Home::booking_confirmation');
$routes->post('webhook', 'CardPayment::webhook');
$routes->match(['get', 'post'], '/payment', 'Home::payment');
$routes->post('card-payment/intent', 'CardPayment::createIntent');
$routes->post('card-payment/store',  'CardPayment::store');
$routes->post('send-receipt', 'Receipt::send');

$routes->post('/checkPromoCode', 'Home::checkPromoCode');

// Login routes
$routes->get('/login', 'Login::index');
$routes->post('/login_submit', 'Login::submit');
$routes->get('/logout', 'Login::logout');
$routes->get('forgot_password', 'AuthController::forgotPasswordForm');
$routes->post('forgot_password', 'AuthController::forgotPassword');
$routes->get('reset_password/(:any)', 'AuthController::resetPasswordForm/$1');
$routes->post('reset_password/(:any)', 'AuthController::resetPassword/$1');

// Admin Portal routes
$routes->get('/admin', 'Admin::index');
$routes->get('/report', 'Admin::report');
$routes->get('/report/export', 'Admin::exportRevenue');
$routes->get('/order', 'Admin::order');
$routes->get('/change_status/(:num)', 'Admin::change_status/$1');
$routes->get('/user', 'Admin::user');
$routes->match(['get', 'post'], '/create_user', 'Admin::create_user');
$routes->get('/order/getDetails/(:num)', 'Admin::getDetails/$1');
$routes->post('/save_note', 'Admin::save_note');
$routes->get('/admin/getRevenueData', 'Admin::getRevenueData');
$routes->get('/admin/getPeakTimesData', 'Admin::getPeakTimesData');
$routes->get('/admin/promo_code', 'PromoCodeController::index');
$routes->get('/admin/promo_code/create', 'PromoCodeController::create');
$routes->post('/admin/promo_code/store', 'PromoCodeController::store');
$routes->get('/admin/promo_code/edit/(:num)', 'PromoCodeController::edit/$1');
$routes->post('/admin/promo_code/update/(:num)', 'PromoCodeController::update/$1');
$routes->get('/admin/promo_code/delete/(:num)', 'PromoCodeController::delete/$1');
$routes->get('/edit_user/(:num)', 'Admin::edit/$1');
$routes->post('/update_user/(:num)', 'Admin::update/$1');
$routes->get('/delete_user/(:num)', 'Admin::delete/$1');
$routes->get('/profile', 'Profile::profile');
$routes->get('/edit_profile/(:num)', 'Profile::edit_profile/$1');
$routes->post('/update_profile/(:num)', 'Profile::update_profile/$1');
$routes->get('/change_password', 'Profile::change_password_form');
$routes->post('/change_password', 'Profile::change_password');
$routes->get('/admin/service_management', 'Admin::service_management');
$routes->post('/admin/service_management/update/(:num)', 'Admin::update_service_price/$1');
$routes->get('order_activity_log/(:num)', 'Admin::order_activity_log/$1');
$routes->get('/admin/refund_request', 'Admin::refund_request');

// Language switch
$routes->get('language/(:segment)', 'LanguageController::change/$1');
$routes->get('/transaction_history', 'Admin::transaction_history');
$routes->get('order_activity_log/(:num)', 'Admin::order_activity_log/$1');
$routes->get('order_activity_log/(:num)', 'Admin::order_activity_log/$1');