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

// route for login
$routes->get('/login', 'LoginController::index');
$routes->post('/auth', 'LoginController::auth');
$routes->get('/logout', 'LoginController::logout');

// route for dashboard
$routes->get('/', 'DashboardController::index', ['filter' => 'auth']);
$routes->add('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

// route for tagihan
$routes->get('/tagihan', 'TagihanController::index', ['filter' => 'auth']);
$routes->get('/tagihan/search/(:any)', 'TagihanController::search_tagihan/$1');
$routes->post('/tagihan/update/(:any)', 'TagihanController::update_by_nim/$1');

// route for pembayaran
$routes->get('/pembayaran', 'PembayaranController::index', ['filter' => 'auth']);
$routes->get('/pembayaran/search/(:any)', 'PembayaranController::search_pembayaran/$1');
$routes->get('/pembayaran/detail-item-tagihan/(:any)', 'PembayaranController::get_detail_item_tagihan_by_paket_id/$1');
$routes->post('/pembayaran/create', 'PembayaranController::add_pembayaran');
$routes->get('/pembayaran/detail/(:any)', 'PembayaranController::get_detail_pembayaran_by_nim/$1');
$routes->get('/pembayaran/delete/(:any)', 'PembayaranController::delete_pembayaran/$1');
$routes->post('/pembayaran/upload-doc-pembayaran', 'PembayaranController::upload_dokumen_pembayaran');

// routes for mahasiswa
$routes->get('/mahasiswa/get/(:any)', 'MahasiswaController::get_mahasiswa_by_id/$1');
$routes->post('/mahasiswa/update/(:any)', 'MahasiswaController::update_mahasiswa/$1');
$routes->delete('/mahasiswa/delete/(:any)', 'MahasiswaController::delete_mahasiswa/$1');

// route for master mahasiswa
$routes->get('/master-mahasiswa', 'Master/MasterMahasiswaController::index', ['as' => 'admin.master_mahasiswa', 'filter' => 'auth']);
$routes->post('/master-mahasiswa/create', 'Master/MasterMahasiswaController::create_mahasiswa');
$routes->post('/master-mahasiswa/import/upload', 'Master/MasterMahasiswaController::import');
$routes->post('/master-mahasiswa/update-tagihan', 'Master/MasterMahasiswaController::update_tagihan_by_nim');

// route for master keuangan
$routes->get('/master-keuangan', 'Master/MasterKeuanganController::index', ['filter' => 'auth']);

// route for master data pendukung
$routes->get('/master-pendukung', 'Master/DataPendukungController::index', ['filter' => 'auth']);
$routes->post('/master-pendukung/create/angkatan', 'AngkatanController::createAngkatan', ['filter' => 'auth']);
$routes->post('/master-pendukung/create/progdi', 'ProgdiController::createProgdi', ['filter' => 'auth']);
$routes->post('/master-pendukung/create/semester', 'SemesterController::createSemester', ['filter' => 'auth']);
$routes->post('/master-pendukung/create/paket', 'PaketController::create_paket', ['filter' => 'auth']);
$routes->post('/master-pendukung/update/angkatan/(:any)', 'AngkatanController::updateAngkatan/$1', ['filter' => 'auth']);
$routes->post('/master-pendukung/update/progdi/(:any)', 'ProgdiController::updateProgdi/$1', ['filter' => 'auth']);
$routes->post('/master-pendukung/update/semester/(:any)', 'SemesterController::updateSemester/$1', ['filter' => 'auth']);
$routes->post('/master-pendukung/update/paket/(:any)', 'PaketController::update_paket/$1', ['filter' => 'auth']);
$routes->delete('/master-pendukung/delete/angkatan/(:any)', 'AngkatanController::deleteAngkatan/$1', ['filter' => 'auth']);
$routes->delete('/master-pendukung/delete/progdi/(:any)', 'ProgdiController::deleteProgdi/$1', ['filter' => 'auth']);
$routes->delete('/master-pendukung/delete/semester/(:any)', 'SemesterController::deleteSemester/$1', ['filter' => 'auth']);
$routes->delete('/master-pendukung/delete/paket/(:any)', 'PaketController::delete_paket/$1', ['filter' => 'auth']);

// route for master backup restore database
$routes->get('/backup-restore', 'Master/BackupRestoreController::index');
$routes->get('/backup-restore/backup', 'Master/BackupRestoreController::backup', ['filter' => 'auth']);
$routes->post('/backup-restore/restore', 'Master/BackupRestoreController::restore');

// route for laporan
$routes->get('/master-laporan', 'Master/LaporanController::index', ['filter' => 'auth']);
$routes->get('/master-laporan/(:any)', 'Master/LaporanController::generate_laporan_tagihan/$1');
$routes->get('/laporan/generate_laporan_tagihan/(:any)', 'Master\LaporanController::generate_laporan_tagihan/$1');
$routes->get('/laporan/generate_laporan_tagihan_all_mhs', 'Master\LaporanController::generate_laporan_tagihan_all_mhs');
$routes->get('/laporan/generate_laporan_pembayaran/(:any)', 'Master\LaporanController::generate_laporan_pembayaran/$1');
$routes->get('/laporan/generate_laporan_pembayaran_all_mhs', 'Master\LaporanController::generate_laporan_pembayaran_all_mhs');
$routes->get('/laporan/generate_laporan_rekam_pembayaran/(:any)', 'Master\LaporanController::generate_laporan_rekam_pembayaran/$1');
$routes->get('/laporan/generate_laporan_rekam_pembayaran_all_mhs', 'Master\LaporanController::generate_laporan_rekam_pembayaran_all_mhs');

// route for cetak tagihan
$routes->get('/cetak-tagihan/by-nim/(:any)', 'CetakTagihan::byNIM/$1');
$routes->get('/cetak-tagihan/by-nim-by-paket/(:any)/(:any)', 'CetakTagihan::byNimPaket/$1/$2');

// route for cetak pembayaran
$routes->get('/cetak-pembayaran/by-nim/(:any)', 'CetakPembayaran::byNIM/$1');
$routes->get('/cetak-pembayaran/by-nim-by-paket/(:any)/(:any)', 'CetakPembayaran::byNIMPaket/$1/$2');
$routes->get('/cetak-pembayaran/by-pembayaran/(:any)', 'CetakPembayaran::byIdPembayaran/$1');

// route for paket
$routes->get('/paket/all', 'PaketController::get_all_paket');
$routes->post('/paket/create', 'PaketController::create_paket');

// route for item paket
$routes->get('/itempaket/find/(:any)', 'ItemPaketController::get_item_paket_by_id/$1');
$routes->post('/itempaket/update/(:any)', 'ItemPaketController::update_item_paket/$1');
$routes->delete('/itempaket/delete/(:any)', 'ItemPaketController::delete_item_paket/$1');
$routes->post('/itempaket/create', 'ItemPaketController::add_item_paket');
$routes->get('/itempaket/(:any)', 'ItemPaketController::get_all_item_by_id_paket/$1');

// route for settings
$routes->get('/settings-account', 'UserController::index', ['filter' => 'auth']);
$routes->get('/settings-account/create', 'UserController::create', ['filter' => 'auth']);
$routes->post('/settings-account/update/$1', 'UserController::update/$1', ['filter' => 'auth']);
$routes->delete('/settings-account/delete/$1', 'UserController::delete/$1', ['filter' => 'auth']);


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
