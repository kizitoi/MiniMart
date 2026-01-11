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
$routes->get('admin/admin', 'Admin::index', ['filter' => 'authGuard']);
$routes->get('institution/institution', 'Institution::index', ['filter' => 'authGuard']);
$routes->get('hostels', 'HostelOwner::index', ['filter' => 'authGuard']);
$routes->get('manager/hostel_manager', 'HostelManager::index', ['filter' => 'authGuard']);
$routes->get('logout', 'Auth::logout'); // Add this route for logout


// User Management Routes
       $routes->group('users', function($routes) {
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

//POLICE Stations

$routes->get('assignments', 'AssignmentController::index');
$routes->get('assignments/create', 'AssignmentController::create');
$routes->post('assignments/store', 'AssignmentController::store');
$routes->get('assignments/edit/(:num)', 'AssignmentController::edit/$1');
$routes->post('assignments/update/(:num)', 'AssignmentController::update/$1');
$routes->get('assignments/delete/(:num)', 'AssignmentController::delete/$1');


//Incidents

$routes->get('incidents', 'IncidentController::index'); // List all incidents
$routes->get('incidents/create', 'IncidentController::create'); // Show add incident form
$routes->post('incidents/store', 'IncidentController::store'); // Store new incident
$routes->get('incidents/edit/(:num)', 'IncidentController::edit/$1'); // Show edit form
//$routes->post('incidents/update/(:num)', 'IncidentController::update/$1'); // Update incident
$routes->get('incidents/delete/(:num)', 'IncidentController::delete/$1'); // Delete incident
$routes->get('incidents/exportExcel', 'IncidentController::exportExcel'); // Export to Excel
$routes->get('incidents/exportPdf', 'IncidentController::exportPdf'); // Export to PDF
$routes->post('incidents/update/(:num)', 'IncidentController::update/$1');

$routes->get('incidents/stats/(:num)', 'Incidents::getIncidentStats/$1');
$routes->get('incidents/chart', 'Incidents::chart');
$routes->get('dashboard', 'IncidentController::dashboard');

$routes->get('officer/officer', 'OfficerController::index');
//$routes->get('officer', 'OfficerController::index');


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



/*
$routes->get('incident_report/generatePdf/(:num)', 'IncidentReportController::generateReportForm/$1');
$routes->post('incident_report/generatePdf', 'IncidentReportController::generatePdf');

$routes->get('incident_report/generateWord/(:num)', 'IncidentReportController::generateReportForm/$1');
$routes->post('incident_report/generateWord', 'IncidentReportController::generateWord');


/*
$routes->get('incident-report/generate/(:num)', 'IncidentReportController::generateReportForm/$1');
$routes->post('incident-report/generate', 'IncidentReportController::generateReport');

// Add these to fix your 404 issue:
$routes->get('incident_report/pdf/(:num)', 'IncidentReportController::generatePdf/$1');
$routes->get('incident_report/word/(:num)', 'IncidentReportController::generateWord/$1');

// Add these to fix your 404 issue:
$routes->get('incident_report/pdf/(:num)', 'IncidentReportController::generatePdf/$1');
$routes->get('incident_report/word/(:num)', 'IncidentReportController::generateWord/$1');



$routes->get('incident_report/form/(:num)', 'IncidentReportController::form/$1');
$routes->post('incident_report/generate', 'IncidentReportController::generate');


*/


///DEPARTMENTS

///ACTION TEKEN

$routes->get('action-taken/(:num)', 'ActionTakenController::index/$1');
$routes->post('action-taken/save/(:num)', 'ActionTakenController::save/$1');
$routes->get('action-taken/delete/(:num)/(:num)', 'ActionTakenController::delete/$1/$2');
///ACTION TEKEN


$routes->get('incident-conclusion/(:num)', 'IncidentConclusionController::index/$1');
$routes->get('incident-conclusion/edit/(:num)', 'IncidentConclusionController::edit/$1');
$routes->post('incident-conclusion/save', 'IncidentConclusionController::save');



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


$routes->get('incident_observations/(:num)', 'IncidentObservationController::index/$1');
$routes->post('incident_observations/save', 'IncidentObservationController::save');
$routes->get('incident_observations/delete/(:num)', 'IncidentObservationController::delete/$1');


///HOSTELS

$routes->get('hostels/hostel_owner', 'HostelOwner::index');
$routes->get('hostels/hostel_owner/view_gallery', 'HostelOwner::view_gallery');

$routes->get('hostels/hostel_owner/add_hostel', 'HostelOwner::add_hostel');
$routes->post('hostels/hostel_owner/save_hostel', 'HostelOwner::save_hostel');

$routes->get('hostels/hostel_owner/edit_hostel/(:num)', 'HostelOwner::edit_hostel/$1');
$routes->post('hostels/hostel_owner/update_hostel/(:num)', 'HostelOwner::update_hostel/$1');

$routes->get('hostels/hostel_owner/map_hostel/(:num)', 'HostelOwner::map_hostel/$1');
$routes->post('hostels/hostel_owner/update_map_hostel/(:num)', 'HostelOwner::update_hostel/$1');

$routes->post('hostels/hostel_owner/delete_hostel/(:num)', 'HostelController::delete_hostel/$1');

//END HOSTELS

$routes->get('company', 'CompanyController::index');
$routes->post('company/save', 'CompanyController::save');
$routes->get('company/edit/(:num)', 'CompanyController::edit/$1');
$routes->post('company/update/(:num)', 'CompanyController::update/$1');
$routes->post('company/delete/(:num)', 'CompanyController::delete/$1');


///INSTITUTION

$routes->get('institution/institution', 'Institution::index');
$routes->get('institution/institution/view_gallery', 'Institution::view_gallery');

$routes->get('institution/institution/add_institution', 'Institution::add_institution');
$routes->post('institution/institution/save_institution', 'Institution::save_institution');

$routes->get('institution/institution/edit_institution/(:num)', 'Institution::edit_institution/$1');
$routes->post('institution/institution/update_institution/(:num)', 'Institution::update_institution/$1');

$routes->get('institution/institution/map_institution/(:num)', 'Institution::map_institution/$1');
$routes->post('institution/institution/update_map_institution/(:num)', 'Institution::update_institution/$1');

$routes->post('institution/institution_owner/delete_institution/(:num)', 'InstitutionController::delete_institution/$1');

//END INSTITUTION


///FLOOR PLANS

$routes->get('/floor-plans/(:num)', 'FloorPlanController::index/$1');
$routes->get('/floor-plans/add/(:num)', 'FloorPlanController::add/$1');
$routes->post('/floor-plans/save', 'FloorPlanController::save');
$routes->get('/floor-plans/edit/(:num)', 'FloorPlanController::edit/$1');
$routes->post('/floor-plans/update/(:num)', 'FloorPlanController::update/$1');
$routes->get('/floor-plans/delete/(:num)', 'FloorPlanController::delete/$1');
///FLOOR PLANS

//STUDENT
$routes->get('student', 'Student::index');
$routes->post('student/likeHostel', 'Student::likeHostel');

$routes->post('student/postComment', 'Student::postComment');
$routes->post('student/editComment', 'Student::editComment');
$routes->post('student/deleteComment', 'Student::deleteComment');

//STUDENT

//GALLERY

$routes->get('gallery/index/(:num)', 'Gallery::index/$1');
$routes->post('gallery/delete_image/(:num)', 'Gallery::delete_image/$1');

//END GALLERY

//BUSINESSES

$routes->get('business', 'BusinessController::index');
$routes->get('business/show/(:segment)', 'BusinessController::show/$1');
$routes->get('business/exportToExcel', 'BusinessController::exportToExcel');

//BUSINESSES

//FIREBASE
$routes->get('firebase', 'FirebaseController::index');
$routes->get('firebase/show/(:segment)', 'FirebaseController::show/$1');

//FIREBASE


///STUDENT DETAILS

$routes->get('/student_details', 'StudentDetailsController::index');
$routes->get('/student_details/create', 'StudentDetailsController::create');
$routes->post('/student_details/store', 'StudentDetailsController::store');
$routes->get('studentdetails', 'StudentDetails::index');
$routes->post('studentdetails/save', 'StudentDetails::save');


////STUDENT DETAILS


// Findings Routes
$routes->get('findings/(:num)', 'FindingController::index/$1');
$routes->post('findings/save', 'FindingController::save');
$routes->get('findings/delete/(:num)', 'FindingController::delete/$1');



///ROLE PERMISSIONS
$routes->group('role_permissions', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'RolePermissionController::index', ['as' => 'role-permissions']);
    $routes->post('save', 'RolePermissionController::save');
});

$routes->get('rolepermissions', 'RolePermissionController::index');
$routes->get('rolepermissions/get_permissions', 'RolePermissionController::get_permissions');
$routes->post('rolepermissions/save', 'RolePermissionController::save');

$routes->get('role-permissions/getPermissions/(:num)', 'RolePermissionsController::getPermissions/$1');
$routes->get('/role-permissions/getPermissions/(:num)', 'Role_permissions::getPermissions/$1');
$routes->get('/role-permissions/view/(:num)', 'RolePermissionsViewController::index/$1');

//$routes->post('role_permissions/updatePermissions', 'Role_permissions::updatePermissions');
$routes->post('role_permissions/updatePermissions', 'RolePermissionController::updatePermissions');



if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
