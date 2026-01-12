<?php
/**
 * Routes Definition
 * Define all application routes here
 */

// Home / Landing
$router->get('/', function() {
    if (is_authenticated()) {
        redirect(base_url('dashboard'));
    }
    redirect(base_url('login'));
});

// =============== Authentication Routes ===============
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// =============== Dashboard Routes ===============
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/stats', 'DashboardController@getStats');

// =============== Inventory Routes ===============
$router->get('/inventory', 'InventoryController@index');
$router->get('/inventory/create', 'InventoryController@create');
$router->post('/inventory/store', 'InventoryController@store');
$router->get('/inventory/edit/{id}', 'InventoryController@edit');
$router->post('/inventory/update/{id}', 'InventoryController@update');
$router->post('/inventory/delete/{id}', 'InventoryController@delete');
$router->get('/inventory/low-stock', 'InventoryController@lowStock');
$router->get('/inventory/export', 'InventoryController@export');

// =============== Finance Routes ===============
$router->get('/finance', 'FinanceController@index');
$router->get('/finance/create', 'FinanceController@create');
$router->get('/finance/edit/{id}', 'FinanceController@edit');
$router->get('/finance/income', 'FinanceController@income');
$router->get('/finance/expense', 'FinanceController@expense');
$router->post('/finance/transaction/store', 'FinanceController@storeTransaction');
$router->post('/finance/update/{id}', 'FinanceController@updateTransaction');
$router->post('/finance/delete/{id}', 'FinanceController@deleteTransaction');
$router->get('/finance/report', 'FinanceController@report');
$router->get('/finance/export', 'FinanceController@export');

// =============== HR Routes ===============
$router->get('/hr', 'HRController@index');
$router->get('/hr/employees', 'HRController@employees');
$router->get('/hr/employee/create', 'HRController@createEmployee');
$router->post('/hr/employee/store', 'HRController@storeEmployee');
$router->get('/hr/employee/edit/{id}', 'HRController@editEmployee');
$router->post('/hr/employee/update/{id}', 'HRController@updateEmployee');
$router->post('/hr/employee/delete/{id}', 'HRController@deleteEmployee');
$router->get('/hr/attendance', 'HRController@attendance');
$router->post('/hr/attendance/record', 'HRController@recordAttendance');
$router->get('/hr/payroll', 'HRController@payroll');
$router->get('/hr/export', 'HRController@export');

// =============== Orders Routes ===============
$router->get('/orders', 'OrderController@index');
$router->get('/orders/create', 'OrderController@create');
$router->post('/orders/store', 'OrderController@store');
$router->get('/orders/detail/{id}', 'OrderController@detail');
$router->post('/orders/update-status/{id}', 'OrderController@updateStatus');
$router->post('/orders/delete/{id}', 'OrderController@delete');
$router->get('/orders/export', 'OrderController@export');

// =============== AI Assistant Routes ===============
$router->get('/ai-assistant', 'AIController@index');
$router->post('/ai-assistant/chat', 'AIController@chat');
$router->get('/ai-assistant/insights', 'AIController@insights');
$router->get('/ai-assistant/recommendations', 'AIController@recommendations');

// =============== Profile Routes ===============
$router->get('/profile', 'ProfileController@index');
$router->post('/profile/update', 'ProfileController@update');
$router->post('/profile/change-password', 'ProfileController@changePassword');

// =============== Settings Routes ===============
$router->get('/settings', 'SettingsController@index');
$router->post('/settings/update', 'SettingsController@update');

// 404 Handler
$router->notFound(function() {
    http_response_code(404);
    view('errors.404');
});
