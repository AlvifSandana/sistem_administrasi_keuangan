<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('DashboardController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'DashboardController::index');
$routes->add('/dashboard', 'DashboardController::index');

// route for tagihan
$routes->get('/tagihan', 'TagihanController::index');
$routes->get('/tagihan/search/(:any)', 'TagihanController::search_tagihan/$1');

// route for pembayaran
$routes->get('/pembayaran', 'PembayaranController::index');
$routes->get('/pembayaran/search/(:any)', 'PembayaranController::search_pembayaran/$1');
$routes->get('/pembayaran/detail-item-tagihan/(:any)', 'PembayaranController::get_detail_item_tagihan_by_paket_id/$1');
$routes->post('/pembayaran/create', 'PembayaranController::add_pembayaran');
$routes->get('/pembayaran/detail/(:any)', 'PembayaranController::get_detail_pembayaran_by_nim/$1');

// routes for mahasiswa
$routes->get('/mahasiswa/get/(:any)', 'MahasiswaController::get_mahasiswa_by_id/$1');
$routes->post('/mahasiswa/update/(:any)', 'MahasiswaController::update_mahasiswa/$1');
$routes->delete('/mahasiswa/delete/(:any)', 'MahasiswaController::delete_mahasiswa/$1');

// route for master mahasiswa
$routes->get('/master-mahasiswa', 'Master/MasterMahasiswaController::index', ['as' => 'admin.master_mahasiswa']);
$routes->post('/master-mahasiswa/create', 'Master/MasterMahasiswaController::create_mahasiswa');
$routes->post('/master-mahasiswa/import/upload', 'Master/MasterMahasiswaController::import');

// route for master keuangan
$routes->get('/master-keuangan', 'Master/MasterKeuanganController::index');

// route for paket
$routes->get('/paket/all', 'PaketController::get_all_paket');
$routes->post('/paket/create', 'PaketController::create_paket');

// route for item paket
$routes->get('/itempaket/find/(:any)', 'ItemPaketController::get_item_paket_by_id/$1');
$routes->post('/itempaket/update/(:any)', 'ItemPaketController::update_item_paket/$1');
$routes->delete('/itempaket/delete/(:any)', 'ItemPaketController::delete_item_paket/$1');
$routes->post('/itempaket/create', 'ItemPaketController::add_item_paket');
$routes->get('/itempaket/(:any)', 'ItemPaketController::get_all_item_by_id_paket/$1');

/*
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
