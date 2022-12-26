<?php

/* 
    Define Krs Routes
*/
$routes->get('pemeriksaan', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::index');
$routes->get('pemeriksaan/(:any)', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::getById/$1');
$routes->post('pemeriksaan/detail/(:any)', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::getDetail/$1');
// $routes->get('pemeriksaan/pasien/(:any)', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::getByPasienId/$1');
$routes->post('pemeriksaan', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::add');
$routes->post('pemeriksaan/rekamMedis', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::exportRekamMedis');
$routes->post('pemeriksaan/resep', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::exportResep');
$routes->put('pemeriksaan/(:any)', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::edit/$1');
$routes->delete('pemeriksaan/(:any)', '\Modules\Pemeriksaan\Controllers\Pemeriksaan::delete/$1');
