<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
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
$routes->get('/', 'AutentikasiController::index', ['as' => 'auth-login']);

$routes->group('auth', function ($routes) {
    $routes->get('login', 'AutentikasiController::index', ['as' => 'auth-login']);
    $routes->post('login', 'AutentikasiController::login', ['as' => 'auth-login-post']);
    $routes->get('logout', 'AutentikasiController::showAuthLogout', ['as' => 'auth-logout']);
    $routes->post('logout', 'AutentikasiController::logout', ['as' => 'auth-logout-post']);
});

$routes->group('pages', function ($routes) {
    $routes->get('maintenance', 'AutentikasiController::show_pages_maintenance', ['as' => 'pages-maintenance']);
    $routes->get('comingsoon', 'AutentikasiController::show_pages_comingsoon', ['as' => 'pages-comingsoon']);
});

// Dashboard
$routes->get('dashboard', 'HomeController::index', [
    'as'     => 'dashboard',
    'filter' => 'auth'
]);

// Data Utama / Master
// $routes->group('master', ['filter' => ['auth', 'role:1']], function ($routes) {
$routes->group('master', ['filter' => 'auth'], function ($routes) {
    $routes->get('pegawai', 'DataUtamaController::showDataPegawai', ['as' => 'master-pegawai']);
    $routes->get('pegawai/data', 'DataUtamaController::getDataPegawai', ['as' => 'master-pegawai-data']);
    $routes->get('pegawai/detail', 'DataUtamaController::getDetailPegawai', ['as' => 'master-detail-pegawai']);
    $routes->post('pegawai/add', 'DataUtamaController::addDetailPegawai', ['as' => 'master-add-pegawai']);
    $routes->patch('pegawai/update', 'DataUtamaController::updateDetailPegawai', ['as' => 'master-update-pegawai']);
    $routes->get('gudang', 'DataUtamaController::showDataGudang', ['as' => 'master-gudang']);
    $routes->get('gudang/data', 'DataUtamaController::getDataGudang', ['as' => 'master-gudang-data']);
    $routes->get('gudang/detail', 'DataUtamaController::getDetailGudang', ['as' => 'master-detail-gudang']);
    $routes->post('gudang/add', 'DataUtamaController::addDetailGudang', ['as' => 'master-add-gudang']);
    $routes->patch('gudang/update', 'DataUtamaController::updateDetailGudang', ['as' => 'master-update-gudang']);
    $routes->get('user-roles/data', 'DataUtamaController::getDataUserRoles', ['as' => 'master-user-roles-data']);
    
    $routes->group('', ['filter' => 'role:1'], function ($routes) {
        $routes->get('user-roles', 'DataUtamaController::showDataUserRoles', ['as' => 'master-user-roles']);
        $routes->get('user-roles/detail', 'DataUtamaController::getDetailUserRoles', ['as' => 'master-detail-user-roles']);
        $routes->post('user-roles/add', 'DataUtamaController::addDetailUserRoles', ['as' => 'master-add-user-roles']);
        $routes->patch('user-roles/update', 'DataUtamaController::updateDetailUserRoles', ['as' => 'master-update-user-roles']);
        $routes->get('user', 'DataUtamaController::showDataUser', ['as' => 'master-user']);
        $routes->get('user/data', 'DataUtamaController::getDataUser', ['as' => 'master-user-data']);
        $routes->get('user/detail', 'DataUtamaController::getDetailUser', ['as' => 'master-detail-user']);
        $routes->post('user/add', 'DataUtamaController::addDetailUser', ['as' => 'master-add-user']);
        $routes->patch('user/update', 'DataUtamaController::updateDetailUser', ['as' => 'master-update-user']);
        $routes->get('kategori-pengeluaran', 'DataUtamaController::showDataKategoriPengeluaran', ['as' => 'master-kategori-pengeluaran']);
        $routes->get('kategori-pengeluaran/data', 'DataUtamaController::getDataKategoriPengeluaran', ['as' => 'master-kategori-pengeluaran-data']);
        $routes->get('kategori-pengeluaran/detail', 'DataUtamaController::getDetailKategoriPengeluaran', ['as' => 'master-detail-kategori-pengeluaran']);
        $routes->post('kategori-pengeluaran/add', 'DataUtamaController::addDetailKategoriPengeluaran', ['as' => 'master-add-kategori-pengeluaran']);
        $routes->patch('kategori-pengeluaran/update', 'DataUtamaController::updateDetailKategoriPengeluaran', ['as' => 'master-update-kategori-pengeluaran']);
    });
});

// Supply Chain
$routes->group('supply-chain', ['filter' => 'auth'], function ($routes) {
    $routes->get('pembelian', 'SupplyChainController::showDataPembelian', ['as' => 'supply-pembelian']);
    $routes->get('pembelian/data', 'SupplyChainController::getDataPembelian', ['as' => 'supply-data-pembelian']);
    $routes->get('pembelian/detail', 'SupplyChainController::getDetailPembelian', ['as' => 'supply-detail-pembelian']);
    $routes->post('pembelian/add', 'SupplyChainController::addDetailPembelian', ['as' => 'supply-add-pembelian']);
    $routes->patch('pembelian/update', 'SupplyChainController::updateDetailPembelian', ['as' => 'supply-update-pembelian']);
    $routes->delete('pembelian/delete/(:num)', 'SupplyChainController::deleteDetailPembelian/$1', ['as' => 'supply-delete-pembelian']);
    $routes->get('pengolahan', 'SupplyChainController::showDataLogPengolahan', ['as' => 'supply-pengolahan']);
    $routes->get('pengolahan/data', 'SupplyChainController::getDataLogPengolahan', ['as' => 'supply-data-pengolahan']);
    $routes->get('pengolahan/detail', 'SupplyChainController::getDetailLogPengolahan', ['as' => 'supply-detail-pengolahan']);
    $routes->post('pengolahan/add', 'SupplyChainController::addDetailLogPengolahan', ['as' => 'supply-add-pengolahan']);
    $routes->patch('pengolahan/update', 'SupplyChainController::updateDetailLogPengolahan', ['as' => 'supply-update-pengolahan']);
    $routes->delete('pengolahan/delete/(:num)', 'SupplyChainController::deleteDetailLogPengolahan/$1', ['as' => 'supply-delete-pengolahan']);
    $routes->get('pengiriman', 'SupplyChainController::showDataPengiriman', ['as' => 'supply-pengiriman']);
    $routes->get('pengiriman/data', 'SupplyChainController::getDataPengiriman', ['as' => 'supply-data-pengiriman']);
    $routes->get('pengiriman/detail', 'SupplyChainController::getDetailPengiriman', ['as' => 'supply-detail-pengiriman']);
    $routes->post('pengiriman/add', 'SupplyChainController::addDetailPengiriman', ['as' => 'supply-add-pengiriman']);
    $routes->patch('pengiriman/update', 'SupplyChainController::updateDetailPengiriman', ['as' => 'supply-update-pengiriman']);
    $routes->delete('pengiriman/delete/(:num)', 'SupplyChainController::deleteDetailPengiriman/$1', ['as' => 'supply-delete-pengiriman']);
    $routes->get('penjualan', 'SupplyChainController::showDataPenjualan', ['as' => 'supply-penjualan']);
    $routes->get('penjualan/data', 'SupplyChainController::getDataPenjualan', ['as' => 'supply-data-penjualan']);
    $routes->get('penjualan/detail', 'SupplyChainController::getDetailPenjualan', ['as' => 'supply-detail-penjualan']);
    $routes->post('penjualan/add', 'SupplyChainController::addDetailPenjualan', ['as' => 'supply-add-penjualan']);
    $routes->patch('penjualan/update', 'SupplyChainController::updateDetailPenjualan', ['as' => 'supply-update-penjualan']);
    $routes->get('penjualan-limbah', 'SupplyChainController::showDataPenjualanLimbah', ['as' => 'supply-penjualan-limbah']);
    $routes->get('penjualan-limbah/data', 'SupplyChainController::getDataPenjualanLimbah', ['as' => 'supply-data-penjualan-limbah']);
    $routes->get('penjualan-limbah/detail', 'SupplyChainController::getDetailPenjualanLimbah', ['as' => 'supply-detail-penjualan-limbah']);
    $routes->post('penjualan-limbah/add', 'SupplyChainController::addDetailPenjualanLimbah', ['as' => 'supply-add-penjualan-limbah']);
    $routes->patch('penjualan-limbah/update', 'SupplyChainController::updateDetailPenjualanLimbah', ['as' => 'supply-update-penjualan-limbah']);
});

// Payroll
$routes->group('finance', ['filter' => 'auth'], function ($routes) {
    $routes->get('pengeluaran', 'FinanceController::showDataPengeluaran', ['as' => 'finance-pengeluaran']);
    $routes->get('pengeluaran/data', 'FinanceController::getDataPengeluaran', ['as' => 'finance-data-pengeluaran']);
    $routes->get('pengeluaran/detail', 'FinanceController::getDetailPengeluaran', ['as' => 'finance-detail-pengeluaran']);
    $routes->post('pengeluaran/add', 'FinanceController::addDetailPengeluaran', ['as' => 'finance-add-pengeluaran']);
    $routes->patch('pengeluaran/update', 'FinanceController::updateDetailPengeluaran', ['as' => 'finance-update-pengeluaran']);
    $routes->get('kasbon', 'FinanceController::showDataKasbon', ['as' => 'finance-kasbon']);
    $routes->get('kasbon/data', 'FinanceController::getDataKasbon', ['as' => 'finance-data-kasbon']);
    $routes->get('kasbon/detail', 'FinanceController::getDetailKasbon', ['as' => 'finance-detail-kasbon']);
    $routes->post('kasbon/add', 'FinanceController::addDetailKasbon', ['as' => 'finance-add-kasbon']);
    $routes->patch('kasbon/update', 'FinanceController::updateDetailKasbon', ['as' => 'finance-update-kasbon']);
    $routes->get('gaji-driver', 'FinanceController::showDataGajiDriver', ['as' => 'finance-gaji-driver']);
    $routes->get('gaji-driver/data', 'FinanceController::getDataUpahDriver', ['as' => 'finance-data-gaji-driver']);
    $routes->post('gaji-driver/add', 'FinanceController::addDetailGajiDriver', ['as' => 'finance-add-gaji-driver']);
    $routes->get('gaji-pegawai', 'FinanceController::showDataGajiPegawai', ['as' => 'finance-gaji-pegawai']);
    $routes->get('gaji-pegawai/data', 'FinanceController::getDataUpahPegawai', ['as' => 'finance-data-gaji-pegawai']);
    $routes->post('gaji-pegawai/add', 'FinanceController::addDetailGajiPegawai', ['as' => 'finance-add-gaji-pegawai']);
});

// Report
$routes->group('report', ['filter' => ['auth', 'role:1']], function ($routes) {
    $routes->get('pengolahan', 'ReportController::showReportPengolahan', ['as' => 'report-pengolahan']);
    $routes->get('pengolahan/data', 'ReportController::getReportPengolahan', ['as' => 'report-data-pengolahan']);
    $routes->get('rendumen-pengolahan/data', 'ReportController::getReportRendumenPengolahan', ['as' => 'report-data-rendumen-pengolahan']);
    $routes->get('komponen-gaji', 'ReportController::showReportKomponenGaji', ['as' => 'report-komponen-gaji']);
    $routes->get('komponen-gaji/data', 'ReportController::getReportKomponenGaji', ['as' => 'report-komponen-gaji-data']);
    $routes->get('gaji-driver', 'ReportController::showReportGajiDriver', ['as' => 'report-gaji-driver']);
    $routes->get('gaji-driver/data', 'ReportController::getReportGajiDriver', ['as' => 'report-data-gaji-driver']);
    $routes->get('gaji-pegawai', 'ReportController::showReportGajiPegawai', ['as' => 'report-gaji-pegawai']);
    $routes->get('gaji-pegawai/data', 'ReportController::getReportGajiPegawai', ['as' => 'report-data-gaji-pegawai']);
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
