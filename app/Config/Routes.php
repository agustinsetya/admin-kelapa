<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AutentikasiController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Authentication
$routes->get('', 'AutentikasiController::index', ['as' => 'auth-login']);

$routes->group('auth', function($routes) {
    $routes->get('login', 'AutentikasiController::index', ['as' => 'auth-login']);
    $routes->post('login', 'AutentikasiController::login', ['as' => 'auth-login-post']);
    $routes->get('logout', 'AutentikasiController::showAuthLogout', ['as' => 'auth-logout']);
    $routes->post('logout', 'AutentikasiController::logout', ['as' => 'auth-logout-post']);
});

$routes->group('pages', function($routes) {
    $routes->get('maintenance', 'AutentikasiController::show_pages_maintenance', ['as' => 'pages-maintenance']);
    $routes->get('comingsoon', 'AutentikasiController::show_pages_comingsoon', ['as' => 'pages-comingsoon']);
});

// Dashboard
$routes->get('dashboard', 'HomeController::index', [
    'as'     => 'dashboard',
    'filter' => 'auth'
]);

// Data Utama / Master
$routes->group('master', ['filter' => 'auth', 'role:1'], function($routes) {
    $routes->get('user-roles', 'DataUtamaController::showDataUserRoles', ['as' => 'master-user-roles']);
    $routes->get('user-roles/data', 'DataUtamaController::getDataUserRoles', ['as' => 'master-user-roles-data']);
    $routes->get('user', 'DataUtamaController::showDataUser', ['as' => 'master-user']);
    $routes->get('user/data', 'DataUtamaController::getDataUser', ['as' => 'master-user-data']);
    $routes->get('pegawai', 'DataUtamaController::showDataPegawai', ['as' => 'master-pegawai']);
    $routes->get('pegawai/data', 'DataUtamaController::getDataPegawai', ['as' => 'master-pegawai-data']);
    $routes->get('gudang', 'DataUtamaController::showDataGudang', ['as' => 'master-gudang']);
    $routes->get('gudang/data', 'DataUtamaController::getDataGudang', ['as' => 'master-gudang-data']);
    $routes->get('komponen-gaji', 'DataUtamaController::showDataKomponenGaji', ['as' => 'master-komponen-gaji']);
    $routes->get('komponen-gaji/data', 'DataUtamaController::getDataKomponenGaji', ['as' => 'master-komponen-gaji-data']);
    $routes->get('komponen-gaji/detail', 'DataUtamaController::getDetailKomponenGaji', ['as' => 'master-komponen-gaji-detail']);
});

// Supply Chain
$routes->group('supply-chain', ['filter' => 'auth'], function($routes) {
    $routes->get('pembelian', 'SupplyChainController::showDataPembelian', ['as' => 'supply-view-pembelian']);
    $routes->get('pengolahan', 'SupplyChainController::showDataPengolahan', ['as' => 'supply-view-pengolahan']);
});

// Payroll
$routes->group('payroll', ['filter' => 'auth'], function($routes) {
    $routes->get('', 'PayrollController::showDataPenggajian', ['as' => 'payroll-view-penggajian']);
    $routes->get('absensi', 'PayrollController::showDataAbsensi', ['as' => 'payroll-view-absensi']);
    $routes->get('lembur', 'PayrollController::showDataLembur', ['as' => 'payroll-view-lembur']);
});

// Contoh untuk filter in filter
// $routes->group('payroll', ['filter' => 'auth'], function($routes) {
//     // umum (login saja)
//     $routes->get('penggajian', 'PenggajianController::index');
//     $routes->get('lembur', 'LemburController::index');

//     // admin-only
//     $routes->group('', ['filter' => 'role:admin'], function($routes) {
//         $routes->post('approve', 'PenggajianController::approve');
//         $routes->delete('hapus/(:num)', 'PenggajianController::delete/$1');
//         $routes->post('import', 'PenggajianController::import');
//     });
// });
// $routes->group('payroll', ['filter' => 'auth'], function($routes) {
//     // bebas diakses (selama sudah login)
//     $routes->get('penggajian', 'PenggajianController::index');
//     $routes->get('lembur', 'LemburController::index');

//     // khusus ADMIN saja
//     $routes->post('approve', 'PenggajianController::approve', ['filter' => 'role:admin']);
//     $routes->delete('hapus/(:num)', 'PenggajianController::delete/$1', ['filter' => 'role:admin']);
// });

// Multi-language functionality
$routes->get('/lang/{locale}', 'Language::index');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
