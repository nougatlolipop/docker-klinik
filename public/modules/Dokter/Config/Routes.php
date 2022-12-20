<?php

/* 
    Define Krs Routes
*/
$routes->get('dokter', '\Modules\Dokter\Controllers\Dokter::index');
$routes->post('dokter', '\Modules\Dokter\Controllers\Dokter::add');
$routes->put('dokter/(:any)', '\Modules\Dokter\Controllers\Dokter::edit/$1');
$routes->delete('dokter/(:any)', '\Modules\Dokter\Controllers\Dokter::delete/$1');
