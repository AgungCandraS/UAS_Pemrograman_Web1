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
$router->get('/hr/employee/delete/{id}', 'HRController@deleteEmployee');
$router->post('/hr/employee/delete/{id}', 'HRController@deleteEmployee');
$router->get('/hr/attendance', 'HRController@attendance');
$router->post('/hr/attendance/record', 'HRController@recordAttendance');
$router->post('/hr/workrecord/store', 'HRController@storeWorkRecord');
$router->post('/hr/workrecord/update/{id}', 'HRController@updateWorkRecord');
$router->get('/hr/workrecord/delete/{id}', 'HRController@deleteWorkRecord');
$router->post('/hr/workrecord/delete/{id}', 'HRController@deleteWorkRecord');
$router->post('/hr/payroll/generate', 'HRController@generatePayroll');
$router->get('/hr/payroll/pay/{id}', 'HRController@payPayroll');
$router->get('/hr/payroll/delete/{id}', 'HRController@deletePayroll');
$router->post('/hr/payroll/delete/{id}', 'HRController@deletePayroll');
$router->get('/hr/payroll', 'HRController@payroll');
$router->get('/hr/payroll/export', 'HRController@exportPayroll');
$router->get('/hr/export', 'HRController@export');

// HR Master Product Routes
$router->post('/hr/product-type/store', 'HRController@storeProductType');
$router->post('/hr/product-type/update/{id}', 'HRController@updateProductType');
$router->post('/hr/product-type/delete/{id}', 'HRController@deleteProductType');

// =============== Sales Routes (formerly Orders) ===============
$router->get('/sales', 'SalesController@index');
$router->get('/sales/create', 'SalesController@create');
$router->post('/sales/store', 'SalesController@store');
$router->get('/sales/view/{id}', 'SalesController@view');
$router->get('/sales/edit/{id}', 'SalesController@edit');
$router->post('/sales/update/{id}', 'SalesController@update');
$router->post('/sales/delete/{id}', 'SalesController@delete');
$router->post('/sales/update-status/{id}', 'SalesController@updateStatus');
$router->get('/sales/print/{id}', 'SalesController@print');

// Admin Fee Settings Routes
$router->get('/sales/admin-fee-settings', 'SalesController@adminFeeSettings');
$router->post('/sales/admin-fee-settings/store', 'SalesController@storeAdminFeeSetting');
$router->post('/sales/admin-fee-settings/update/{id}', 'SalesController@updateAdminFeeSetting');
$router->post('/sales/admin-fee-settings/delete/{id}', 'SalesController@deleteAdminFeeSetting');
$router->post('/sales/admin-fee-settings/toggle/{id}', 'SalesController@toggleAdminFeeSetting');

// =============== Orders Routes (Legacy - Redirect to Sales) ===============
$router->get('/orders', function() { redirect(base_url('sales')); });
$router->get('/orders/create', function() { redirect(base_url('sales/create')); });
$router->post('/orders/store', function() { redirect(base_url('sales/store')); });
$router->get('/orders/detail/{id}', function($id) { redirect(base_url('sales/view/' . $id)); });
$router->post('/orders/update-status/{id}', function($id) { redirect(base_url('sales/update-status/' . $id)); });
$router->post('/orders/delete/{id}', function($id) { redirect(base_url('sales/delete/' . $id)); });
$router->get('/orders/export', function() { redirect(base_url('sales')); });

// =============== AI Assistant Routes ===============
$router->get('/ai-assistant', 'AIController@index');
$router->post('/ai-assistant/chat', 'AIController@chat');
$router->post('/ai-assistant/clear-history', 'AIController@clearHistory');
$router->get('/ai-assistant/insights', 'AIController@insights');
$router->get('/ai-assistant/recommendations', 'AIController@recommendations');

// =============== Profile Routes ===============
$router->get('/profile', 'ProfileController@index');
$router->post('/profile/update', 'ProfileController@update');
$router->post('/profile/change-password', 'ProfileController@changePassword');

// =============== Settings Routes ===============
$router->get('/settings', 'SettingsController@index');
$router->post('/settings/update', 'SettingsController@update');

// =============== Storage Routes (Serve uploaded files) ===============
$router->get('/storage/{directory}/{filename}', 'StorageController@serve');

// Platform Management (dari Sales Admin Fees)
$router->post('/settings/save-platform', 'SettingsController@savePlatform');
$router->get('/settings/delete-platform/{id}', 'SettingsController@deletePlatform');

// Product Type Management (dari HR Master Produk)
$router->post('/settings/save-product', 'SettingsController@saveProduct');
$router->get('/settings/delete-product/{id}', 'SettingsController@deleteProduct');

// Legacy Category & Employee routes (kept for compatibility)
$router->post('/settings/save-category', 'SettingsController@saveCategory');
$router->get('/settings/delete-category/{id}', 'SettingsController@deleteCategory');
$router->post('/settings/save-employee', 'SettingsController@saveEmployee');
$router->get('/settings/delete-employee/{id}', 'SettingsController@deleteEmployee');

// 404 Handler
$router->notFound(function() {
    http_response_code(404);
    view('errors.404');
});
