<?php

/* 
    Define Krs Routes
*/
$routes->get('pasien', '\Modules\Pasien\Controllers\Pasien::index');
$routes->get('pasien/(:any)', '\Modules\Pasien\Controllers\Pasien::getById/$1');
$routes->post('pasien', '\Modules\Pasien\Controllers\Pasien::add');
// $routes->put('pasien/(:any)', '\Modules\Pasien\Controllers\Pasien::edit/$1');
// $routes->delete('pasien/(:any)', '\Modules\Pasien\Controllers\Pasien::delete/$1');
