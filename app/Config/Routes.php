<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/register', 'Register::index');
$routes->post('/register/save', 'Register::save');

$routes->get('profile', 'ProfileController::index');
$routes->post('profile/update', 'ProfileController::updateProfile');
$routes->post('profile/change-password', 'ProfileController::changePassword');

$routes->get('student/student', 'Student::index', ['filter' => 'authGuard']);
$routes->get('officer/officer', 'OfficerController::index', ['filter' => 'authGuard']);


$routes->get('system_administration', 'SystemAdministration::index');

$routes->get('items/mobile', 'Items::mobile');



$routes->get('logout', 'Auth::logout'); // Add this route for logout

$routes->get('api/check_payment_status', 'MpesaCallback::checkStatus');


// User Management Routes
$routes->group('users', function($routes)
{
           // List users
 $routes->get('/', 'UserController::index');
           // Show add/edit form
 $routes->get('form/(:num)?', 'UserController::form/$1');
           // Save user (both add and edit)
 $routes->post('save', 'UserController::save');
           // Delete user
 $routes->get('delete/(:num)', 'UserController::delete/$1');
           // Send password reset link to user
 $routes->get('sendPasswordReset/(:num)', 'UserController::sendPasswordReset/$1');
});

       // Password reset route
       // This would be the route where the user can securely reset their password
       // The password reset page would typically take a token parameter.
       $routes->get('reset-password', 'PasswordResetController::index');
       $routes->post('reset-password', 'PasswordResetController::resetPassword');
      // $routes->get('reset-password', 'PasswordResetController::index', ['filter' => 'noauth']);

//POLICE Stations

$routes->get('assignments', 'AssignmentController::index');
$routes->get('assignments/create', 'AssignmentController::create');
$routes->post('assignments/store', 'AssignmentController::store');
$routes->get('assignments/edit/(:num)', 'AssignmentController::edit/$1');
$routes->post('assignments/update/(:num)', 'AssignmentController::update/$1');
$routes->get('assignments/delete/(:num)', 'AssignmentController::delete/$1');

$routes->get('verify-email', 'EmailVerificationController::verify');


//////

$routes->get('customer_accounts/(:num)', 'CustomerAccounts::index/$1');
$routes->post('customer_accounts/add/(:num)', 'CustomerAccounts::add/$1');
$routes->post('customer_accounts/markPaid', 'CustomerAccounts::markPaid');

$routes->get('customer_accounts/exportStatement/(:num)', 'CustomerAccounts::exportStatement/$1');



$routes->get('admin/system_documentation', 'SystemDocumentation::index');
$routes->get('admin/system_documentation/add', 'SystemDocumentation::add');
$routes->post('admin/system_documentation/store', 'SystemDocumentation::store');
$routes->get('admin/system_documentation/edit/(:num)', 'SystemDocumentation::edit/$1');
$routes->post('admin/system_documentation/update/(:num)', 'SystemDocumentation::update/$1');
$routes->get('admin/system_documentation/delete/(:num)', 'SystemDocumentation::delete/$1');

// For view/download only (users)
$routes->get('system_documentation/view', 'SystemDocumentation::viewList');
$routes->get('system_documentation/download/(:num)', 'SystemDocumentation::download/$1');

// Main CRUD + search/pagination
$routes->get('reminders',                 'Reminders::index');
$routes->get('reminders/add',             'Reminders::add');
$routes->post('reminders/store',          'Reminders::store');
$routes->get('reminders/edit/(:num)',     'Reminders::edit/$1');
$routes->post('reminders/update/(:num)',  'Reminders::update/$1');
$routes->get('reminders/delete/(:num)',   'Reminders::delete/$1');

// JSON endpoint for today's popups (footer)
$routes->get('reminders/today',           'Reminders::today');


$routes->get('payment_methods', 'PaymentMethods::index'); // this handles without "/"
$routes->get('payment_methods/create', 'PaymentMethods::create');
$routes->post('payment_methods/store', 'PaymentMethods::store');
$routes->get('payment_methods/edit/(:num)', 'PaymentMethods::edit/$1');
$routes->post('payment_methods/update/(:num)', 'PaymentMethods::update/$1');
$routes->get('payment_methods/delete/(:num)', 'PaymentMethods::delete/$1');



$routes->get('payment_descriptions', 'PaymentDescriptions::index');
$routes->get('payment_descriptions/create', 'PaymentDescriptions::create');
$routes->post('payment_descriptions/store', 'PaymentDescriptions::store');
$routes->get('payment_descriptions/edit/(:num)', 'PaymentDescriptions::edit/$1');
$routes->post('payment_descriptions/update/(:num)', 'PaymentDescriptions::update/$1');
$routes->get('payment_descriptions/delete/(:num)', 'PaymentDescriptions::delete/$1');


//$routes->resource('payment_descriptions');
//$routes->resource('payment_methods');

$routes->get('customer_accounts/printReceipt/(:num)', 'CustomerAccounts::printReceipt/$1');



//////

//Incidents

$routes->get('incidents', 'IncidentController::index'); // List all incidents
$routes->get('incidents/create', 'IncidentController::create'); // Show add incident form
$routes->post('incidents/store', 'IncidentController::store'); // Store new incident
$routes->get('incidents/edit/(:num)', 'IncidentController::edit/$1'); // Show edit form
$routes->get('incidents/delete/(:num)', 'IncidentController::delete/$1'); // Delete incident
$routes->get('incidents/exportExcel', 'IncidentController::exportExcel'); // Export to Excel
$routes->get('incidents/exportPdf', 'IncidentController::exportPdf'); // Export to PDF
$routes->post('incidents/update/(:num)', 'IncidentController::update/$1');

$routes->get('incidents/stats/(:num)', 'Incidents::getIncidentStats/$1');
$routes->get('incidents/chart', 'Incidents::chart');
$routes->get('dashboard', 'IncidentController::dashboard');

$routes->get('officer/officer', 'OfficerController::index');

///INCIDENTS
//NATURE OF INCIDENTS

$routes->get('nature-of-incidents', 'NatureOfIncidentController::index');
$routes->get('nature-of-incidents/create', 'NatureOfIncidentController::create');
$routes->post('nature-of-incidents/store', 'NatureOfIncidentController::store');
$routes->get('nature-of-incidents/edit/(:num)', 'NatureOfIncidentController::edit/$1');
$routes->post('nature-of-incidents/update/(:num)', 'NatureOfIncidentController::update/$1');
$routes->get('nature-of-incidents/delete/(:num)', 'NatureOfIncidentController::delete/$1');

///NATURE OF INCIDENTS///
///ROLES

$routes->get('roles', 'Roles::index');
$routes->get('roles/create', 'Roles::create');
$routes->post('roles/store', 'Roles::store');
$routes->get('roles/edit/(:num)', 'Roles::edit/$1');
$routes->post('roles/update/(:num)', 'Roles::update/$1');
$routes->get('roles/delete/(:num)', 'Roles::delete/$1');

///ROLES
//Departments

$routes->get('departments', 'DepartmentController::index');
$routes->get('departments/create', 'DepartmentController::create');
$routes->post('departments/store', 'DepartmentController::store');
$routes->get('departments/edit/(:num)', 'DepartmentController::edit/$1');
$routes->post('departments/update/(:num)', 'DepartmentController::update/$1');
$routes->post('departments/delete/(:num)', 'DepartmentController::delete/$1');

$routes->get('incident_report/generate/(:num)', 'IncidentReportController::generateReportForm/$1');
$routes->post('incident_report/generate', 'IncidentReportController::generateReport');

$routes->get('incident_report/generatePdf/(:num)/(:num)/(:num)', 'IncidentReportController::generatePdf/$1/$2/$3');
$routes->get('incident_report/generateWord/(:num)/(:num)/(:num)', 'IncidentReportController::generateWord/$1/$2/$3');
$routes->get('incidents/preview/(:num)', 'IncidentController::preview/$1');
$route['incident/image/(:num)/(:any)'] = 'IncidentController/viewIncidentImage/$1/$2';

$routes->get('action-taken/(:num)', 'ActionTakenController::index/$1'); // Show list of actions for incident
$routes->post('action-taken/save/(:num)', 'ActionTakenController::save/$1'); // Save new action
$routes->get('action-taken/edit/(:num)/(:num)', 'ActionTakenController::edit/$1/$2'); // Show edit form
$routes->post('action-taken/update/(:num)/(:num)', 'ActionTakenController::update/$1/$2'); // Update existing action
$routes->get('action-taken/delete/(:num)/(:num)', 'ActionTakenController::delete/$1/$2'); // Delete action

///ACTION TEKEN


$routes->get('incident-conclusion/(:num)', 'IncidentConclusionController::index/$1');
$routes->get('incident-conclusion/edit/(:num)', 'IncidentConclusionController::edit/$1');
$routes->post('incident-conclusion/save', 'IncidentConclusionController::save');

$routes->post('items/save_label_size', 'Items::save_label_size');


////CUSTOMERS
$routes->get('customers', 'CustomerController::index');
$routes->get('customers/form', 'CustomerController::form');
$routes->get('customers/form/(:num)', 'CustomerController::form/$1');
$routes->post('customers/save', 'CustomerController::save');
$routes->get('customers/delete/(:num)', 'CustomerController::delete/$1');
////CUSTOMERS


$routes->get('incident_pictures/(:num)', 'IncidentPictureController::index/$1');
$routes->get('incident_pictures/add/(:num)', 'IncidentPictureController::add/$1');
$routes->post('incident_pictures/save', 'IncidentPictureController::save');
$routes->get('incident_pictures/delete/(:num)', 'IncidentPictureController::delete/$1');
$routes->get('incident_pictures/view_image/(:num)', 'IncidentPictureController::viewImage/$1');



$routes->get('incident_observations/(:num)', 'IncidentObservationController::index/$1');
$routes->post('incident_observations/save', 'IncidentObservationController::save');
$routes->get('incident_observations/delete/(:num)', 'IncidentObservationController::delete/$1');




$routes->get('company', 'CompanyController::index');
$routes->post('company/save', 'CompanyController::save');
$routes->get('company/edit/(:num)', 'CompanyController::edit/$1');
$routes->post('company/update/(:num)', 'CompanyController::update/$1');
$routes->post('company/delete/(:num)', 'CompanyController::delete/$1');


//FIREBASE
$routes->get('firebase', 'FirebaseController::index');
$routes->get('firebase/show/(:segment)', 'FirebaseController::show/$1');
//FIREBASE

// Findings Routes
$routes->get('findings/(:num)', 'FindingController::index/$1');
$routes->post('findings/save', 'FindingController::save');
$routes->get('findings/delete/(:num)', 'FindingController::delete/$1');
$routes->get('findings/edit/(:num)', 'FindingController::edit/$1');
$routes->post('findings/update/(:num)', 'FindingController::update/$1');
///ROLE PERMISSIONS
$routes->group('role_permissions', ['namespace' => 'App\Controllers'], function($routes)
{
$routes->get('/', 'RolePermissionController::index', ['as' => 'role-permissions']);
$routes->post('save', 'RolePermissionController::save');
});
$routes->get('rolepermissions', 'RolePermissionController::index');
$routes->get('rolepermissions/get_permissions', 'RolePermissionController::get_permissions');
$routes->post('rolepermissions/save', 'RolePermissionController::save');
$routes->get('role-permissions/getPermissions/(:num)', 'RolePermissionsController::getPermissions/$1');
$routes->get('/role-permissions/getPermissions/(:num)', 'Role_permissions::getPermissions/$1');
$routes->get('/role-permissions/view/(:num)', 'RolePermissionsViewController::index/$1');
$routes->post('role_permissions/updatePermissions', 'RolePermissionController::updatePermissions');


$routes->get('permissions', 'Permissions::index');
$routes->get('permissions/add', 'Permissions::add');
$routes->post('permissions/store', 'Permissions::store');
$routes->get('permissions/edit/(:num)', 'Permissions::edit/$1');
$routes->post('permissions/update/(:num)', 'Permissions::update/$1');
$routes->get('permissions/delete/(:num)', 'Permissions::delete/$1');








$routes->get('notifications/(:num)', 'UserNotificationController::edit/$1');
$routes->post('notifications/update/(:num)', 'UserNotificationController::update/$1');
$routes->get('notifications/(:num)', 'UserNotificationController::edit/$1');
$routes->get('user-notifications/(:num)', 'UserNotificationController::edit/$1');
$routes->get('bulksms', 'BulkSMSController::index');
$routes->post('bulksms/save', 'BulkSMSController::save');

$routes->get('chat/load/(:num)', 'ChatController::loadChat/$1');
$routes->post('chat/send', 'ChatController::send');

$routes->get('/session/check', 'Auth::checkSession');
$routes->get('private-image/(:any)/(:num)', 'User::servePrivateImage/$1/$2');
$routes->get('view-image/(:num)/(:segment)', 'UserController::viewPrivateImage/$1/$2');



$routes->get('item_categories', 'ItemCategories::index');
$routes->get('item_categories/create', 'ItemCategories::create');
$routes->post('item_categories/store', 'ItemCategories::store');
$routes->get('item_categories/delete/(:num)', 'ItemCategories::delete/$1');
$routes->get('item_categories/edit/(:num)', 'ItemCategories::edit/$1');
$routes->post('item_categories/update/(:num)', 'ItemCategories::update/$1');

$routes->get('unit_of_measure', 'UnitOfMeasure::index');
$routes->get('unit_of_measure/create', 'UnitOfMeasure::create');
$routes->post('unit_of_measure/store', 'UnitOfMeasure::store');
$routes->post('unit_of_measure/delete/(:num)', 'UnitOfMeasure::delete/$1');
$routes->get('unit_of_measure/edit/(:num)', 'UnitOfMeasure::edit/$1');
$routes->post('unit_of_measure/update/(:num)', 'UnitOfMeasure::update/$1');

$routes->get('vat_settings', 'VatSettings::index');
$routes->get('vat_settings/create', 'VatSettings::create');
$routes->post('vat_settings/store', 'VatSettings::store');
$routes->get('vat_settings/edit/(:num)', 'VatSettings::edit/$1');
$routes->post('vat_settings/update/(:num)', 'VatSettings::update/$1');
$routes->post('vat_settings/delete/(:num)', 'VatSettings::delete/$1');

$routes->get('items', 'Items::index');
$routes->get('items/create', 'Items::create');
$routes->post('items/store', 'Items::store');
$routes->get('items/edit/(:num)', 'Items::edit/$1');
$routes->post('items/update/(:num)', 'Items::update/$1');
$routes->post('items/delete/(:num)', 'Items::delete/$1');


$routes->get('numbering_settings', 'NumberingSettings::index');
$routes->get('numbering_settings/create', 'NumberingSettings::create');
$routes->post('numbering_settings/store', 'NumberingSettings::store');
$routes->get('numbering_settings/edit/(:num)', 'NumberingSettings::edit/$1');
$routes->post('numbering_settings/update/(:num)', 'NumberingSettings::update/$1');
$routes->get('numbering_settings/delete/(:num)', 'NumberingSettings::delete/$1');

$routes->get('items_units_of_measure/(:num)', 'ItemUnitsOfMeasure::index/$1');
$routes->get('items_units_of_measure/create/(:num)', 'ItemUnitsOfMeasure::create/$1');
$routes->post('items_units_of_measure/store', 'ItemUnitsOfMeasure::store');
$routes->get('items_units_of_measure/edit/(:num)', 'ItemUnitsOfMeasure::edit/$1');
$routes->post('items_units_of_measure/update/(:num)', 'ItemUnitsOfMeasure::update/$1');
$routes->post('items_units_of_measure/delete/(:num)', 'ItemUnitsOfMeasure::delete/$1');

$routes->get('suppliers', 'SuppliersController::index');
$routes->get('suppliers/create', 'SuppliersController::create');
$routes->post('suppliers/store', 'SuppliersController::store');
$routes->get('suppliers/edit/(:num)', 'SuppliersController::edit/$1');
$routes->post('suppliers/update/(:num)', 'SuppliersController::update/$1');
$routes->post('suppliers/delete/(:num)', 'SuppliersController::delete/$1');

$routes->get('shops', 'ShopsController::index');
$routes->get('shops/create', 'ShopsController::create');
$routes->post('shops/store', 'ShopsController::store');
$routes->get('shops/edit/(:num)', 'ShopsController::edit/$1');
$routes->post('shops/update/(:num)', 'ShopsController::update/$1');
$routes->post('shops/delete/(:num)', 'ShopsController::delete/$1');

$routes->get('item_check_in', 'ItemCheckIn::index');
$routes->get('item_check_in/create', 'ItemCheckIn::create');
$routes->post('item_check_in/store', 'ItemCheckIn::store');

$routes->get('pos_list', 'PosList::index');

$routes->get('/pos', 'PosController::index');
$routes->get('/pos/getCategoriesByShop/(:num)', 'PosController::getCategoriesByShop/$1');
$routes->get('/pos/getItemsByCategory/(:num)', 'PosController::getItemsByCategory/$1');

$routes->get('items/by_category/(:num)', 'Items::byCategory/$1');


$routes->post('orders/create', 'Orders::create');
$routes->get('orders/current', 'Orders::current'); // Optional: Add a "current" page if needed
$routes->post('sales/add', 'SalesController::add');


$routes->get('salesorder/current', 'SalesOrderController::current');
$routes->post('salesorder/close', 'SalesOrderController::close');
$routes->get('salesorder/receipt/(:num)', 'SalesOrderController::receipt/$1');

$routes->get('salesorder/orderPdf/(:num)', 'SalesOrderController::orderPdf/$1');




$routes->get('sales-order-history', 'SalesOrderController::history');
$routes->get('receipt/(:num)', 'ItemsController::receipt/$1'); // assuming receipt function is in ItemsController
$routes->post('salesorder/remove_item', 'SalesOrderController::remove_item');

$routes->get('discount_settings', 'DiscountSettings::index');
$routes->get('discount_settings/create', 'DiscountSettings::create');
$routes->post('discount_settings/store', 'DiscountSettings::store');
$routes->get('discount_settings/edit/(:num)', 'DiscountSettings::edit/$1');
$routes->post('discount_settings/update/(:num)', 'DiscountSettings::update/$1');
$routes->get('discount_settings/delete/(:num)', 'DiscountSettings::delete/$1');

$routes->get('item_categories', 'ItemCategories::index');
$routes->get('item_categories/export/(:any)', 'ItemCategories::export/$1');
$routes->post('items/generate_barcodes', 'Items::generate_barcodes');
$routes->get('items/express-sale', 'Items::expressSale');
$routes->get('items/express_sale', 'Items::expressSale');

$routes->get('reports', 'Reports::index');
$routes->get('reports/view/(:num)', 'Reports::view/$1');

$routes->get('cashier-sales-report', 'CashierSalesReport::index');
$routes->get('cashier-sales-report/export/(:segment)', 'CashierSalesReport::export/$1');

$routes->get('reorder-report', 'ReorderLevelReport::index');
$routes->get('reorder-report/export/(:segment)', 'ReorderLevelReport::export/$1');

$routes->get('inventory', 'InventoryReportController::index');
$routes->get('inventoryreport/exportPdf', 'InventoryReportController::exportPdf');
$routes->get('inventoryreport/exportWord', 'InventoryReportController::exportWord');
$routes->get('inventoryreport/exportExcel', 'InventoryReportController::exportExcel');

$routes->get('inventory-report', 'InventoryReportController::index');
$routes->get('inventory-report/export/(:segment)', 'InventoryReportController::export/$1');

$routes->get('sales-orders-report', 'SalesOrderReportController::index');
$routes->get('sales-orders-report/pdf', 'SalesOrderReportController::exportPdf');
$routes->get('sales-orders-report/word', 'SalesOrderReportController::exportWord');
$routes->get('sales-orders-report/excel', 'SalesOrderReportController::exportExcel');
$routes->get('items/searchAjax', 'Items::searchItemAjax');

$routes->get('mpesa_settings', 'MpesaSettings::index');
$routes->get('mpesa_settings/edit/(:num)', 'MpesaSettings::edit/$1');
$routes->post('mpesa_settings/update/(:num)', 'MpesaSettings::update/$1');
$routes->get('mpesa_transactions', 'MpesaTransactions::index');
$routes->post('mpesa/callback', 'MpesaTransactions::callback');
//$routes->get('mpesa_transactions', 'MpesaTransactions::index');
$routes->post('api/mpesa_callback', 'MpesaTransactions::callback');
$routes->get('api/check_payment_status', 'MpesaTransactions::checkStatus');



if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
